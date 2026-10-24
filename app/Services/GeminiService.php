<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected array $fallbackModels = [
        'gemini-flash-lite-latest',
        'gemini-3.1-flash-lite',
        'gemini-3.6-flash',
        'gemini-3.5-flash',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
        $this->model = config('services.gemini.model') ?? env('GEMINI_MODEL', 'gemini-flash-lite-latest');
    }

    /**
     * Fetch real-time cinema data from the database to inject into AI context.
     */
    protected function getDatabaseContext(): string
    {
        try {
            $movies = Movie::with(['showtimes' => function ($q) {
                $q->whereDate('show_date', '>=', now()->toDateString())
                  ->with('theater')
                  ->orderBy('show_date')
                  ->orderBy('start_time');
            }])->get();

            if ($movies->isEmpty()) {
                return "Currently, no movies are scheduled in the CineMax database.";
            }

            $context = "=== CineMax Live Database Information ===\n";
            foreach ($movies as $movie) {
                $context .= "- Film: {$movie->title} ({$movie->release_year}) | Rating: {$movie->rating}/10\n";
                if (!empty($movie->description)) {
                    $context .= "  Overview: " . Str::limit($movie->description, 130) . "\n";
                }

                if ($movie->showtimes->isNotEmpty()) {
                    $context .= "  Available Showtimes:\n";
                    foreach ($movie->showtimes as $st) {
                        $dateStr = $st->show_date ? $st->show_date->format('Y-m-d') : 'Today';
                        $hall = $st->theater ? "{$st->theater->name} ({$st->theater->type})" : 'General Hall';
                        $context .= "    * Date: {$dateStr} at {$st->start_time} | Hall: {$hall} | Ticket Price: {$st->price} EGP\n";
                    }
                } else {
                    $context .= "  Available Showtimes: (No upcoming showtimes currently)\n";
                }
            }

            return $context;
        } catch (\Throwable $e) {
            Log::warning("Could not load database context for Gemini: " . $e->getMessage());
            return "";
        }
    }

    public function generateResponse(
        string $userMessage,
        array $history = []
    ): string {
        $contents = [];

        // 1. Map Conversation History
        foreach ($history as $message) {
            if (empty($message['role']) || empty($message['text'])) {
                continue;
            }

            $contents[] = [
                'role' => $message['role'] === 'user' ? 'user' : 'model',
                'parts' => [
                    ['text' => $message['text']]
                ],
            ];
        }

        // 2. Append Current User Message
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ],
        ];

        // 3. Build Payload with Google System Instruction & Live Database Context
        $today = date('Y-m-d');
        $dbContext = $this->getDatabaseContext();

        $systemPrompt = "You are 'CineBot AI', the smart virtual concierge and booking assistant for CineMax Cinema.
            Today's date is: {$today}. Always be aware of the current date and year.
            Answer naturally in Arabic or English based on the language the user speaks. Be friendly, concise, and helpful.

            CINEMA DATABASE CONTEXT (LIVE DATA):
            {$dbContext}

            INSTRUCTIONS FOR USING THIS DATABASE CONTEXT:
            1. When the user asks about movies currently playing, available films, showtimes, theaters/halls, or ticket prices, ALWAYS rely on and quote the CineMax Live Database above.
            2. Provide exact details when asked (e.g. Hall name, screening time, and price in EGP).
            3. If a user asks for recommendations, prioritize movies currently available in our cinema from the list above.
            4. If a user asks about a movie that is NOT in the database list, politely explain that it is not currently playing in CineMax, but give them friendly trivia or suggest similar available movies from our catalog.
            5. If the user wants to book, guide them to click on the movie details and select their desired showtime and seats.";

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'contents' => $contents
        ];

        $modelsToTry = array_unique(array_merge([$this->model], $this->fallbackModels));

        foreach ($modelsToTry as $model) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";

                $response = Http::timeout(10)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, $payload);

                if ($response->successful()) {
                    $text = $response->json('candidates.0.content.parts.0.text');
                    if (!empty($text)) {
                        return trim($text);
                    }
                }

                $errMsg = $response->json('error.message') ?? $response->body();
                Log::warning("Gemini model {$model} failed: " . substr($errMsg, 0, 200));

            } catch (\Exception $e) {
                Log::warning("Gemini model {$model} exception: " . $e->getMessage());
            }
        }

        return 'عذراً، حدث خطأ مؤقت في الاتصال، يرجى المحاولة مرة أخرى.';
    }
}

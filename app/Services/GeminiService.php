<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        // 3. Build Payload with Google System Instruction
        $today = date('Y-m-d');

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => "You are 'CineBot AI', a smart and helpful AI Assistant for CineMax. 
                    Today's date is: {$today}. Always be aware of the current date and year.
                    Answer in Arabic or English based on the user's language. Be concise, friendly, and helpful with movie recommendations, cinema details, and questions."]
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

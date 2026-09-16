<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotRequest;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function chat(ChatbotRequest $request)
    {
        $validated = $request->validated();

        $reply = $this->geminiService->generateResponse(
            $validated['message'],
            $validated['history'] ?? []
        );

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatbotSecurityService;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function public(
        Request $request,
        ChatbotSecurityService $security,
        ChatbotService $chatbot
    ): JsonResponse {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1000'],
            'page' => ['nullable', 'string', 'max:100'],
        ]);

        $message = $security->sanitizeText($validated['message']);

        if ($security->detectPromptInjection($message)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf tidak bisa, silakan bertanya tentang HIMATIK PNJ dan proses rekrutmen.',
            ], 400);
        }

        $result = $chatbot->reply(
            message: $message,
            context: 'public',
            page: $validated['page'] ?? null,
            codeRequested: $security->detectCodeRequest($message)
        );

        return response()->json([
            'status' => 'ok',
            'reply' => $result['reply'],
            'source' => $result['source'],
        ]);
    }

    public function admin(
        Request $request,
        ChatbotSecurityService $security,
        ChatbotService $chatbot
    ): JsonResponse {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1000'],
            'page' => ['nullable', 'string', 'max:100'],
        ]);

        $message = $security->sanitizeText($validated['message']);

        if ($security->detectPromptInjection($message)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf tidak bisa, silakan bertanya tentang HIMATIK PNJ dan proses rekrutmen.',
            ], 400);
        }

        $result = $chatbot->reply(
            message: $message,
            context: 'admin',
            user: $request->user(),
            page: $validated['page'] ?? null,
            codeRequested: $security->detectCodeRequest($message)
        );

        return response()->json([
            'status' => 'ok',
            'reply' => $result['reply'],
            'source' => $result['source'],
        ]);
    }
}

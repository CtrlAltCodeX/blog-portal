<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGptService
{
    public function getMessage($prompt)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' .  config('services.openai.key'),
        ])->post(config('services.openai.url'),  [
            "model" => "gpt-3.5-turbo",
            'messages' => [
                [
                    "role" => "user",
                    "content" => $prompt
                ],
            ],
            'temperature' => 0.5,
            "max_tokens" => 1000,
            "top_p" => 1.0,
            "frequency_penalty" => 0.52,
            "presence_penalty" => 0.5,
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            return [
                'status' => false,
                'message' => $data['error']['message'] ?? 'API Error',
            ];
        }

        $content = $data['choices'][0]['message']['content'] ?? '';

        return [
            'status' => true,
            'content' => $content,
        ];
    }
}

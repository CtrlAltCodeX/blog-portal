<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;



class ChatGptController extends Controller
{
    public function openAi()
    {
        return view('chatGpt.new_request');
    }

    public function responseAiDescription(Request $request)
    {
        $data = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                ])->post(env('OPENAI_API_URL'), [
                    "model" => "gpt-3.5-turbo",
                    'messages' => [
                       [
                           "role" => "user",
                           "content" => $request->product_info
                       ],
                    ],
                    'temperature' => 0.5,

                    "max_tokens" => 200,

                    "top_p" => 1.0,

                    "frequency_penalty" => 0.52,

                    "presence_penalty" => 0.5,

                    "stop" => ["11."],
                    ])
                  ->json();
                  $user_request = $request->product_info;
                  $assistent_response = $data['choices'][0]['message'];
                return view('chatGpt.new_request',compact('user_request','assistent_response'));
    }
}

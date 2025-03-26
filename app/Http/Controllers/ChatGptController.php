<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatGptController extends Controller
{
    public function __construct()
    {
        $this->middleware('role_or_permission:Product Listing > AI Chat Bots', ['only' => ['openAi']]);
    }

    public function openAi()
    {
        return view('chatGpt.new_request');
    }

    public function responseAiDescription(Request $request)
    {
        try {
            $data = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->post(env('OPENAI_API_URL'), [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    [
                        "role" => "user",
                        "content" => $request->product_info
                    ],
                ],
                'temperature' => 0.5,
                "max_tokens" => 500,
                "top_p" => 1.0,
                "frequency_penalty" => 0.52,
                "presence_penalty" => 0.5,
                "stop" => ["11."],
            ])->json();

            if (isset($data['error'])) {
                session()->flash('error', 'Something went Wrong!!');
                return redirect()->back();
            } else {
                $user_request = $request->product_info;
                $assistent_response = $data['choices'][0]['message'];

                return view('chatGpt.new_request', compact('user_request', 'assistent_response'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Something went Wrong!!');

            return redirect()->back();
        }
    }
}

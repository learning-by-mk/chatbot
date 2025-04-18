<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatBotController extends Controller
{
    public function chat(Request $request, string $uuid = '')
    {
        $chat = null;
        if ($uuid) {
            $chat = Chat::where('uuid', $uuid)->first();
            if (!$chat) {
                abort(404);
            }
            if ($chat->user_id !== Auth::id()) {
                abort(403);
            }
        }
        $chats = Chat::where('user_id', Auth::id())->latest()->get();
        return view('chat.index', compact('chats', 'chat'));
    }

    public function show(string $uuid)
    {
        $chat = Chat::where('uuid', $uuid)->first();
        if (!$chat) {
            abort(404);
        }
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }
        return view('chat.show', compact('chat'));
    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'title' => $request->title ?? 'New Chat',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.',
                ],
            ],
        ]);

        return redirect()->route('chat.show', $chat);
    }

    public function chatApi(Request $request, int $chatId)
    {
        $request->validate([
            'message' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,txt,docx,doc,jpg,png,jpeg,gif,svg,webp',
        ]);

        $chat = Chat::find($chatId);
        if (!$chat) {
            $uuid = Str::uuid();
            $chat = Chat::create([
                'user_id' => Auth::id(),
                'title' => $request->message,
                'uuid' => $uuid,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                ],
            ]);
        }

        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        $message = $request->message;
        $file = $request->file;

        $messages = $chat->messages;
        $messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        // $messages[] = [
        //     'role' => 'assistant',
        //     'content' => 'Hello, how can I help you today?' . $message,
        // ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROK_API_KEY'),
        ])->post('https://api.x.ai/v1/chat/completions', [
            'model' => 'grok-2-latest',
            // TODO: chỉnh sửa messages phù hợp với cuộc hội thoại
            'messages' => $messages,
            'stream' => false,
            'temperature' => 0,
        ]);

        if (isset($response->json()['choices'][0]['message'])) {
            $messages[] = $response->json()['choices'][0]['message'];
        } else {
            $messages[] = [
                'role' => 'assistant',
                'content' => 'Lỗi khi trả lời, vui lòng thử lại sau.',
            ];
        }

        $chat->update([
            'messages' => $messages,
            'last_message' => $message,
        ]);

        return ['messages' => $messages, 'chat' => $chat];
        // return response()->json($response->json());
    }
}

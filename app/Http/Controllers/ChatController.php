<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chats = QueryBuilder::for(Chat::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return ChatResource::collection($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $data = $request->validated();
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'title' => $data['title'] ?? 'New Chat',
            'chatbot_question_id' => $data['chatbot_question_id'],
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant.',
                ],
            ],
        ]);
        return new ChatResource($chat);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Chat $chat)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chat = $chat->load($with_vals);
        return new ChatResource($chat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatRequest $request, Chat $chat)
    {
        $data = $request->validated();
        $chat->update($data);
        return new ChatResource($chat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        try {
            $chat->delete();
            return response()->json([
                'status' => true,
                'message' => 'Chat deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Chat not deleted'
            ], 500);
        }
    }

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

    public function showByUuid(Request $request, string $uuid)
    {
        $chat = Chat::where('uuid', $uuid)->first();
        if (!$chat) {
            abort(404);
        }
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chat = $chat->load($with_vals);
        return new ChatResource($chat);
    }

    public function chatApi(Request $request, ?Chat $chat = null)
    {
        $request->validate([
            'message' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,txt,docx,doc,jpg,png,jpeg,gif,svg,webp',
        ]);

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

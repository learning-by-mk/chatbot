<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Document;
use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI;
use PhpOffice\PhpWord\IOFactory;
use Spatie\PdfToText\Pdf;

class ChatController extends Controller
{
    private $client;
    private $geminiApiKey;
    private $openai;

    public function __construct()
    {
        $this->client = new Client();
        $this->geminiApiKey = env('GEMINI_API_KEY');
        $this->openai = OpenAI::client(env('OPENAI_API_KEY'));
    }

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

    public function chatApi(Request $request, string $chat_id)
    {
        $chat = Chat::find($chat_id);
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'message' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,txt,docx,doc,jpg,png,jpeg,gif,svg,webp',
        ]);

        $document = Document::find($request->document_id);
        $file = $document->file;
        $path = Storage::disk('public')->path($file->path);
        $content = $this->extractFileContent($path, $file->ext);

        if (!$chat) {
            $uuid = Str::uuid();
            $chat = Chat::create([
                'user_id' => Auth::id(),
                'title' => $request->message,
                'uuid' => $uuid,
                'document_id' => $request->document_id,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Here is the content of the document: ' . $content,
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

    public function summary(Request $request, Document $document)
    {
        $file = $document->file;
        $path = Storage::disk('public')->path($file->path);
        $content = $this->extractFileContent($path, $file->ext);
        $summary = $this->summarizeWithGemini($content);

        return [
            'status' => true,
            'message' => 'Tóm tắt tài liệu thành công',
            'summary' => $summary,
        ];
    }

    public function convertToSpeech(Request $request, Document $document)
    {
        $file = $document->file;
        $path = Storage::disk('public')->path($file->path);
        $content = $this->extractFileContent($path, $file->ext);
        $audioPath = $this->convertToSpeechWithGemini($content);

        return [
            'status' => true,
            'message' => 'Chuyển đổi thành công',
            'audio_path' => $audioPath,
        ];
    }

    private function extractFileContent($filePath, $extension)
    {
        switch (strtolower($extension)) {
            case 'txt':
                return file_get_contents($filePath);

            case 'pdf':
                return Pdf::getText($filePath); // Yêu cầu pdftotext đã cài

            case 'docx':
                /**
                 * @var \PhpOffice\PhpWord\Document $phpWord
                 */
                $phpWord = IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;

            default:
                throw new \Exception('Định dạng file không được hỗ trợ.');
        }
    }

    private function summarizeWithGemini($content)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $this->geminiApiKey;

        $prompt = "Tóm tắt nội dung sau thành 5-10 câu, tập trung vào các ý chính:\n" . $content;

        try {
            $response = $this->client->post($url, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không thể tóm tắt.';
        } catch (\Exception $e) {
            throw new \Exception('Lỗi khi gọi Gemini API: ' . $e->getMessage());
        }
    }

    private function convertToSpeechWithGemini($text)
    {
        try {
            $client = new TextToSpeechClient();
            $request = new \Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest();

            $synthesisInput = new \Google\Cloud\TextToSpeech\V1\SynthesisInput();
            $synthesisInput->setText($text);
            $request->setInput($synthesisInput);

            $voice = new \Google\Cloud\TextToSpeech\V1\VoiceSelectionParams();
            $voice->setLanguageCode('vi-VN');
            $voice->setName('vi-VN-Standard-A');
            $request->setVoice($voice);

            $audioConfig = new \Google\Cloud\TextToSpeech\V1\AudioConfig();
            $audioConfig->setAudioEncoding(\Google\Cloud\TextToSpeech\V1\AudioEncoding::MP3);
            $request->setAudioConfig($audioConfig);

            $response = $client->synthesizeSpeech($request);
            $audioContent = $response->getAudioContent();

            $filename = 'tts_' . time() . Str::uuid() . '.mp3';
            Storage::disk('public')->put('audio/' . $filename, $audioContent);
            // Storage::disk('public')->store('audio', $filename, $audioContent);
            return $filename;
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi Gemini API (text-to-speech): ' . $e->getMessage());
            throw new \Exception('Lỗi khi gọi Gemini API: ' . $e->getMessage());
        }
    }
}

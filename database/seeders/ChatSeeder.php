<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $users = User::all();
        // $chatbotQuestions = ChatbotQuestion::all();

        // for ($i = 0; $i < 10; $i++) {
        //     $messages = json_encode([
        //         [
        //             'role' => 'user',
        //             'content' => 'Xin chào, tôi có một số thắc mắc về tài liệu này.',
        //             'timestamp' => now()->timestamp,
        //         ],
        //         [
        //             'role' => 'assistant',
        //             'content' => 'Xin chào! Tôi có thể giúp gì cho bạn về tài liệu này?',
        //             'timestamp' => now()->addMinutes(1)->timestamp,
        //         ],
        //         [
        //             'role' => 'user',
        //             'content' => 'Bạn có thể tóm tắt nội dung chính không?',
        //             'timestamp' => now()->addMinutes(2)->timestamp,
        //         ],
        //         [
        //             'role' => 'assistant',
        //             'content' => 'Dĩ nhiên rồi! Đây là phần tóm tắt nội dung chính của tài liệu...',
        //             'timestamp' => now()->addMinutes(3)->timestamp,
        //         ],
        //     ]);

        //     Chat::create([
        //         'uuid' => Str::uuid(),
        //         'user_id' => $users->random()->id,
        //         'title' => 'Cuộc trò chuyện #' . ($i + 1),
        //         'last_message' => 'Đây là phần tóm tắt nội dung chính của tài liệu...',
        //         'messages' => $messages,
        //         'chatbot_question_id' => $chatbotQuestions->random()->id,
        //     ]);
        // }
    }
}

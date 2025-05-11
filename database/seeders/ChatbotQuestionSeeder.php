<?php

namespace Database\Seeders;

use App\Models\ChatbotQuestion;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatbotQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $documents = Document::all();

        for ($i = 0; $i < 10; $i++) {
            ChatbotQuestion::create([
                'user_id' => $users->random()->id,
                'document_id' => $documents->random()->id,
            ]);
        }
    }
}

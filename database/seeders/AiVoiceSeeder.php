<?php

namespace Database\Seeders;

use App\Models\AiVoice;
use App\Models\Document;
use Illuminate\Database\Seeder;

class AiVoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = Document::all();

        for ($i = 0; $i < 10; $i++) {
            AiVoice::create([
                'document_id' => $documents->random()->id,
                'audio_path' => 'voices/document_audio_' . ($i + 1) . '.mp3',
            ]);
        }
    }
}

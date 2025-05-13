<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $documents = Document::all();

        // Đảm bảo không trùng lặp các cặp user-document
        $userDocumentPairs = [];

        while (count($userDocumentPairs) < 10) {
            $userId = $users->random()->id;
            $documentId = $documents->random()->id;
            $pair = $userId . '-' . $documentId;

            if (!in_array($pair, $userDocumentPairs)) {
                $userDocumentPairs[] = $pair;

                Favorite::updateOrCreate([
                    'user_id' => $userId,
                    'document_id' => $documentId,
                ]);
            }
        }
    }
}

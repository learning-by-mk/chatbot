<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $documents = Document::all();

        foreach ($documents as $document) {
            $document->categories()->sync($categories->random()->id);
        }
    }
}

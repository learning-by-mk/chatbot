<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Ai',
                'slug' => 'ai',
                'description' => 'Ai topic',
            ],
            [
                'name' => 'Machine Learning',
                'slug' => 'machine-learning',
                'description' => 'Machine Learning topic',
            ],
            [
                'name' => 'Chuyển đổi số',
                'slug' => 'chuyen-doi-so',
                'description' => 'Chuyển đổi số topic',
            ],
            [
                'name' => 'Phát triển bền vững',
                'slug' => 'phat-trien-ben-vung',
                'description' => 'Phát triển bền vững topic',
            ],
            [
                'name' => 'Giáo dục',
                'slug' => 'giao-duc',
                'description' => 'Giáo dục topic',
            ],
            [
                'name' => 'Tài chính',
                'slug' => 'tai-chinh',
                'description' => 'Tài chính topic',
            ],
            [
                'name' => 'Lãnh đạo',
                'slug' => 'lanh-dao',
                'description' => 'Lãnh đạo topic',
            ],
            [
                'name' => 'Đổi mới sáng tạo',
                'slug' => 'doi-moi-sang-tao',
                'description' => 'Đổi mới sáng tạo topic',
            ],
        ];

        foreach ($topics as $topic) {
            Topic::updateOrCreate([
                'slug' => $topic['slug'],
            ], $topic);
        }
    }
}

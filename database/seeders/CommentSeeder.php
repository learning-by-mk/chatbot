<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $documents = Document::all();

        $comments = [
            'Tài liệu rất hữu ích, cảm ơn tác giả nhiều!',
            'Nội dung chi tiết và dễ hiểu.',
            'Tôi đã học được rất nhiều từ tài liệu này.',
            'Hay và bổ ích, sẽ giới thiệu cho bạn bè.',
            'Cách trình bày khá rõ ràng và logic.',
            'Tài liệu khá tốt nhưng cần cập nhật thêm.',
            'Đây là một trong những tài liệu tốt nhất về chủ đề này.',
            'Rất hữu ích cho công việc của tôi.',
            'Nội dung dễ hiểu đối với người mới bắt đầu.',
            'Tài liệu chất lượng, đáng để tham khảo!',
        ];

        $scores = [3, 4, 5, 4, 5, 3, 5, 4, 5, 4];

        for ($i = 0; $i < 40; $i++) {
            Comment::create([
                'document_id' => $documents->random()->id,
                'user_id' => $users->random()->id,
                'comment' => $comments[array_rand($comments)],
                'score' => $scores[array_rand($scores)],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Công nghệ thông tin',
                'description' => 'Tài liệu về CNTT, lập trình, phát triển phần mềm',
                'status' => 'active',
            ],
            [
                'name' => 'Kinh tế',
                'description' => 'Tài liệu về kinh tế, tài chính, quản trị kinh doanh',
                'status' => 'active',
            ],
            [
                'name' => 'Y học',
                'description' => 'Tài liệu y khoa, dược phẩm, chăm sóc sức khỏe',
                'status' => 'active',
            ],
            [
                'name' => 'Giáo dục',
                'description' => 'Tài liệu giáo dục, giảng dạy, học tập',
                'status' => 'active',
            ],
            [
                'name' => 'Luật',
                'description' => 'Tài liệu về luật pháp, quy định, chính sách',
                'status' => 'active',
            ],
            [
                'name' => 'Khoa học',
                'description' => 'Tài liệu khoa học cơ bản và ứng dụng',
                'status' => 'active',
            ],
            [
                'name' => 'Kỹ thuật',
                'description' => 'Tài liệu kỹ thuật, công nghệ, máy móc',
                'status' => 'active',
            ],
            [
                'name' => 'Nghệ thuật',
                'description' => 'Tài liệu về nghệ thuật, thiết kế, sáng tạo',
                'status' => 'active',
            ],
            [
                'name' => 'Ngoại ngữ',
                'description' => 'Tài liệu học ngoại ngữ, ngôn ngữ học',
                'status' => 'active',
            ],
            [
                'name' => 'Khác',
                'description' => 'Các tài liệu không thuộc các danh mục trên',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

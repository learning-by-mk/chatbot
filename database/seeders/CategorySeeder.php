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
                'name' => 'Công nghệ',
                'description' => 'Khám phá các tài liệu về công nghệ mới nhất',
                'status' => 'active',
                'icon' => 'Code',
                'slug' => 'technology',
            ],
            [
                'name' => 'Kinh tế',
                'description' => 'Tài liệu về kinh tế, tài chính, quản trị kinh doanh',
                'status' => 'active',
                'icon' => 'LineChart',
                'slug' => 'economics',
            ],
            [
                'name' => 'Khoa học',
                'description' => 'Tài liệu về khoa học, kỹ thuật, y tế, khoa học tự nhiên',
                'status' => 'active',
                'icon' => 'Brain',
                'slug' => 'science',
            ],
            [
                'name' => 'Giáo dục',
                'description' => 'Tài liệu về giáo dục, giáo trình, bài giảng, tài liệu giáo dục',
                'status' => 'active',
                'icon' => 'GraduationCap',
                'slug' => 'education',
            ],
            [
                'name' => 'Văn học',
                'description' => 'Tài liệu về văn học, sách, truyện, tài liệu văn học',
                'status' => 'active',
                'icon' => 'Book',
                'slug' => 'literature',
            ],
            [
                'name' => 'Luận văn',
                'description' => 'Tài liệu về luận văn, đề tài nghiên cứu, luận án',
                'status' => 'active',
                'icon' => 'FileText',
                'slug' => 'thesis',
            ],
            [
                'name' => 'Doanh nghiệp',
                'description' => 'Tài liệu về doanh nghiệp, khởi nghiệp, quản trị doanh nghiệp',
                'status' => 'active',
                'icon' => 'Building',
                'slug' => 'business',
            ],
            [
                'name' => 'Sáng tạo',
                'description' => 'Tài liệu về sáng tạo, đổi mới, tư duy sáng tạo',
                'status' => 'active',
                'icon' => 'Lightbulb',
                'slug' => 'creativity',
            ],
        ];

        foreach ($categories as $category) {
            $category = Category::updateOrCreate(['slug' => $category['slug']], $category);
            $category->update(['href' => '/documents?filter[categories]=' . $category->id]);
        }
    }
}

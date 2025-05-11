<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        $documents = [
            [
                'title' => 'Hướng dẫn Laravel 12',
                'description' => 'Tài liệu hướng dẫn chi tiết Laravel 12 cho người mới bắt đầu',
                'file_path' => 'documents/laravel12-guide.docx',
                'pdf_path' => 'documents/laravel12-guide.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Cơ bản về Machine Learning',
                'description' => 'Giới thiệu cơ bản về học máy và các ứng dụng phổ biến',
                'file_path' => 'documents/machine-learning-basic.docx',
                'pdf_path' => 'documents/machine-learning-basic.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Kinh tế vĩ mô',
                'description' => 'Tài liệu giảng dạy về kinh tế vĩ mô',
                'file_path' => 'documents/macro-economics.docx',
                'pdf_path' => 'documents/macro-economics.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Quản trị doanh nghiệp',
                'description' => 'Các phương pháp quản trị doanh nghiệp hiệu quả',
                'file_path' => 'documents/business-management.docx',
                'pdf_path' => 'documents/business-management.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Dược lý học đại cương',
                'description' => 'Tài liệu dược lý học cho sinh viên y dược',
                'file_path' => 'documents/pharmacology.docx',
                'pdf_path' => 'documents/pharmacology.pdf',
                'status' => 'pending',
            ],
            [
                'title' => 'Phương pháp giảng dạy hiệu quả',
                'description' => 'Các phương pháp giảng dạy hiện đại và hiệu quả',
                'file_path' => 'documents/teaching-methods.docx',
                'pdf_path' => 'documents/teaching-methods.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Luật Doanh nghiệp 2023',
                'description' => 'Cập nhật luật doanh nghiệp mới nhất',
                'file_path' => 'documents/enterprise-law-2023.docx',
                'pdf_path' => 'documents/enterprise-law-2023.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Vật lý lượng tử cơ bản',
                'description' => 'Tài liệu về vật lý lượng tử cho sinh viên',
                'file_path' => 'documents/quantum-physics.docx',
                'pdf_path' => 'documents/quantum-physics.pdf',
                'status' => 'approved',
            ],
            [
                'title' => 'Thiết kế đồ họa với Adobe Photoshop',
                'description' => 'Hướng dẫn thiết kế đồ họa chuyên nghiệp',
                'file_path' => 'documents/graphic-design.docx',
                'pdf_path' => 'documents/graphic-design.pdf',
                'status' => 'pending',
            ],
            [
                'title' => 'Tiếng Anh giao tiếp cơ bản',
                'description' => 'Tài liệu học tiếng Anh giao tiếp cho người mới',
                'file_path' => 'documents/english-communication.docx',
                'pdf_path' => 'documents/english-communication.pdf',
                'status' => 'approved',
            ],
        ];

        foreach ($documents as $index => $document) {
            Document::create([
                'author_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => $document['title'],
                'description' => $document['description'],
                'file_path' => $document['file_path'],
                'pdf_path' => $document['pdf_path'],
                'status' => $document['status'],
                'uploaded_by' => $users->random()->id,
            ]);
        }
    }
}

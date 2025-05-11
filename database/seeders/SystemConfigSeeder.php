<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'key' => 'site_name',
                'value' => 'Document Management System',
                'description' => 'Tên của hệ thống quản lý tài liệu',
            ],
            [
                'key' => 'site_description',
                'value' => 'Hệ thống quản lý và chia sẻ tài liệu trực tuyến',
                'description' => 'Mô tả ngắn về hệ thống',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@example.com',
                'description' => 'Email của quản trị viên hệ thống',
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10',
                'description' => 'Kích thước tối đa của file tải lên (MB)',
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,doc,docx,ppt,pptx,xls,xlsx',
                'description' => 'Các loại file được phép tải lên',
            ],
            [
                'key' => 'default_language',
                'value' => 'vi',
                'description' => 'Ngôn ngữ mặc định của hệ thống',
            ],
            [
                'key' => 'items_per_page',
                'value' => '20',
                'description' => 'Số lượng mục hiển thị trên mỗi trang',
            ],
            [
                'key' => 'enable_registration',
                'value' => 'true',
                'description' => 'Cho phép người dùng đăng ký tài khoản mới',
            ],
            [
                'key' => 'require_approval',
                'value' => 'true',
                'description' => 'Yêu cầu quản trị viên phê duyệt tài liệu mới',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'description' => 'Chế độ bảo trì hệ thống',
            ],
        ];

        foreach ($configs as $config) {
            SystemConfig::create($config);
        }
    }
}

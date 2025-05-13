<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Document Management System',
                'description' => 'Tên của hệ thống quản lý tài liệu',
                'setting_group_id' => 1,
            ],
            [
                'key' => 'site_description',
                'value' => 'Hệ thống quản lý và chia sẻ tài liệu trực tuyến',
                'description' => 'Mô tả ngắn về hệ thống',
                'setting_group_id' => 1,
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@example.com',
                'description' => 'Email của quản trị viên hệ thống',
                'setting_group_id' => 3,
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10',
                'description' => 'Kích thước tối đa của file tải lên (MB)',
                'setting_group_id' => 1,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,doc,docx,ppt,pptx,xls,xlsx',
                'description' => 'Các loại file được phép tải lên',
                'setting_group_id' => 1,
            ],
            [
                'key' => 'default_language',
                'value' => 'vi',
                'description' => 'Ngôn ngữ mặc định của hệ thống',
                'setting_group_id' => 1,
            ],
            [
                'key' => 'items_per_page',
                'value' => '100',
                'description' => 'Số lượng mục hiển thị trên mỗi trang',
                'setting_group_id' => 1,
            ],
            [
                // giới hạn số lần nhập sai mật khẩu
                'key' => 'max_login_attempts',
                'value' => '5',
                'description' => 'Giới hạn số lần nhập sai mật khẩu',
                'setting_group_id' => 4,
            ],
            [
                'key' => 'lockout_time',
                'value' => '10',
                'description' => 'Thời gian khóa tài khoản (phút)',
                'setting_group_id' => 4,
            ],
            [
                'key' => 'grok_key',
                'value' => 'grok_key',
                'description' => 'Khóa API của Grok',
                'setting_group_id' => 2,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate([
                'key' => $setting['key'],
            ], [
                'value' => $setting['value'],
                'description' => $setting['description'],
                'setting_group_id' => $setting['setting_group_id'],
            ]);
        }
    }
}

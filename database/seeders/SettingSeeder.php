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
                'title' => 'Email',
                'sub_title' => 'Gửi email cho quản trị viên',
                'description' => 'Email của quản trị viên hệ thống',
                'setting_group_id' => 3,
            ],
            [
                'key' => 'company_email',
                'value' => 'company@example.com',
                'title' => 'Email',
                'sub_title' => 'Gửi email cho chúng tôi',
                'description' => 'Email của công ty',
                'setting_group_id' => 3,
                'icon' => 'Mail',
            ],
            // Mail, Phone, MapPin, MessageSquare
            [
                'key' => 'company_phone',
                'value' => '0000000000',
                'title' => 'Điện thoại',
                'sub_title' => 'Thứ 2 - Thứ 6, 8:00 - 17:00',
                'description' => 'Số điện thoại của công ty',
                'setting_group_id' => 3,
                'icon' => 'Phone',
            ],
            [
                'key' => 'company_address',
                'value' => 'Hà Nội',
                'title' => 'Địa chỉ',
                'description' => 'Địa chỉ của công ty',
                'setting_group_id' => 3,
                'icon' => 'MapPin',
            ],
            [
                'key' => 'social_networks',
                'value' => 'children',
                'title' => 'Mạng xã hội',
                'description' => 'Theo dõi chúng tôi',
                'setting_group_id' => 3,
                'icon' => 'MessageSquare',
                'children' => [
                    [
                        'key' => 'facebook',
                        'value' => 'https://www.facebook.com/co.cai.nit.00000',
                        'setting_group_id' => 3,
                        'icon' => 'facebook',
                    ],
                    [
                        'key' => 'instagram',
                        'value' => 'https://www.instagram.com/vanmanh_00/',
                        'setting_group_id' => 3,
                        'icon' => 'twitter',
                    ],
                ],
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
            $setting_collection = collect($setting);
            $setting = Setting::updateOrCreate([
                'key' => $setting_collection->get('key'),
            ], $setting_collection->except('children')->toArray());

            if ($setting_collection->has('children') && is_array($setting_collection->get('children'))) {
                $children = $setting_collection->get('children');
                $children_collection = collect($children);
                foreach ($children_collection as $child) {
                    $child_collection = collect($child);
                    $child = Setting::updateOrCreate([
                        'key' => $child_collection->get('key'),
                    ], [
                        ...$child_collection->toArray(),
                        'parent_id' => $setting->id,
                    ]);
                }
            }
        }
    }
}

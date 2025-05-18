<?php

namespace Database\Seeders;

use App\Models\AuthorProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo vai trò author tồn tại
        if (!Role::where('name', 'author')->exists()) {
            Role::create(['name' => 'author', 'guard_name' => 'web']);
        }

        $authors = [
            [
                'user' => [
                    'name' => 'Nguyễn Nhật Ánh',
                    'email' => 'nhatanhwriter@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Nguyễn Nhật Ánh là một trong những nhà văn được yêu thích nhất Việt Nam, nổi tiếng với nhiều tác phẩm văn học thiếu nhi và thanh thiếu niên.',
                    'education' => 'Trường Đại học Sư phạm Huế',
                    'specialization' => 'Văn học thiếu nhi, truyện ngắn',
                    'awards' => 'Giải thưởng Văn học ASEAN',
                ]
            ],
            [
                'user' => [
                    'name' => 'Tô Hoài',
                    'email' => 'tohoai@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Tô Hoài là nhà văn nổi tiếng của Việt Nam với nhiều tác phẩm văn học dành cho thiếu nhi và người lớn.',
                    'education' => 'Tự học',
                    'specialization' => 'Truyện đồng thoại, tiểu thuyết',
                    'awards' => 'Giải thưởng Hồ Chí Minh về Văn học Nghệ thuật',
                ]
            ],
            [
                'user' => [
                    'name' => 'Nguyễn Ngọc Tư',
                    'email' => 'ngoctu@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Nguyễn Ngọc Tư là nhà văn nữ nổi tiếng với những tác phẩm mang đậm hơi thở Nam Bộ.',
                    'education' => 'Đại học Sư phạm TP.HCM',
                    'specialization' => 'Truyện ngắn, tiểu thuyết',
                    'awards' => 'Giải thưởng Hội Nhà văn Việt Nam',
                ]
            ],
            [
                'user' => [
                    'name' => 'Nguyễn Quang Thân',
                    'email' => 'quangthan@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Nguyễn Quang Thân là nhà văn, nhà báo, từng là biên tập viên báo Văn Nghệ và tạp chí Văn Nghệ Quân Đội.',
                    'education' => 'Trường Đại học Văn hóa Hà Nội',
                    'specialization' => 'Tiểu thuyết lịch sử, truyện ngắn',
                    'awards' => 'Giải thưởng Văn học Việt Nam',
                ]
            ],
            [
                'user' => [
                    'name' => 'Bảo Ninh',
                    'email' => 'baoninh@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Bảo Ninh là nhà văn nổi tiếng với tác phẩm "Nỗi buồn chiến tranh", được dịch ra nhiều thứ tiếng.',
                    'education' => 'Trường Đại học Tổng hợp Hà Nội',
                    'specialization' => 'Văn học chiến tranh',
                    'awards' => 'Giải thưởng Văn học Châu Á',
                ]
            ],
            [
                'user' => [
                    'name' => 'Nguyễn Phong Việt',
                    'email' => 'phongviet@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Nguyễn Phong Việt là nhà thơ trẻ được nhiều bạn đọc yêu thích với những bài thơ sâu lắng về tình yêu và cuộc sống.',
                    'education' => 'Đại học Kinh tế TP.HCM',
                    'specialization' => 'Thơ tình, tản văn',
                    'awards' => 'Giải thưởng Thơ hay',
                ]
            ],
            [
                'user' => [
                    'name' => 'Anh Khang',
                    'email' => 'anhkhang@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Anh Khang là tác giả trẻ với nhiều tác phẩm bestseller về chủ đề tình yêu, được độc giả trẻ yêu thích.',
                    'education' => 'Đại học Văn Lang',
                    'specialization' => 'Tản văn, truyện ngắn',
                    'awards' => 'Giải thưởng Sách hay',
                ]
            ],
            [
                'user' => [
                    'name' => 'Nguyễn Ngọc Thạch',
                    'email' => 'ngocthach@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Nguyễn Ngọc Thạch là nhà văn trẻ với nhiều tác phẩm gây tranh cãi nhưng cũng rất được yêu thích.',
                    'education' => 'Đại học Khoa học Xã hội và Nhân văn TP.HCM',
                    'specialization' => 'Tiểu thuyết đương đại',
                    'awards' => 'Giải thưởng Sáng tạo trẻ',
                ]
            ],
            [
                'user' => [
                    'name' => 'Hà Minh Hoàng',
                    'email' => 'minhhoang@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Hà Minh Hoàng là tác giả của nhiều sách kinh tế, kinh doanh được độc giả đánh giá cao.',
                    'education' => 'Thạc sĩ Quản trị Kinh doanh, Đại học Harvard',
                    'specialization' => 'Sách kinh tế, khởi nghiệp',
                    'awards' => 'Giải thưởng Sách hay về Kinh tế',
                ]
            ],
            [
                'user' => [
                    'name' => 'Rosie Nguyễn',
                    'email' => 'rosienguyenvn@gmail.com',
                    'password' => Hash::make('password'),
                ],
                'profile' => [
                    'biography' => 'Rosie Nguyễn là tác giả của nhiều cuốn sách self-help được yêu thích tại Việt Nam.',
                    'education' => 'Đại học Ngoại thương Hà Nội',
                    'specialization' => 'Sách self-help, du ký',
                    'awards' => 'Giải thưởng Sách hay về Phát triển bản thân',
                ]
            ],
        ];

        foreach ($authors as $author) {
            // Tạo user
            $user = User::updateOrCreate([
                'email' => $author['user']['email']
            ], $author['user']);
            $user->assignRole('user');

            // Tạo profile
            $author['profile']['user_id'] = $user->id;
            $authorProfile = AuthorProfile::create($author['profile']);
            $authorProfile->updateStatistics();
        }
    }
}

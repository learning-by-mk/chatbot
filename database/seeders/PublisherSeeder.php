<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            [
                'name' => 'NXB Giáo Dục',
                'address' => '81 Trần Hưng Đạo, Hà Nội',
                'email' => 'info@nxbgd.vn',
                'phone' => '024-38221917',
                'website' => 'https://www.nxbgd.vn',
                'description' => 'Nhà xuất bản Giáo dục Việt Nam là nhà xuất bản chủ đạo ở Việt Nam trong việc xuất bản sách và tài liệu giáo dục.',
            ],
            [
                'name' => 'NXB Kim Đồng',
                'address' => '55 Quang Trung, Hà Nội',
                'email' => 'kimdong@nxbkimdong.vn',
                'phone' => '024-39434490',
                'website' => 'https://www.nxbkimdong.com.vn',
                'description' => 'Nhà xuất bản Kim Đồng là nhà xuất bản hàng đầu về sách thiếu nhi và truyện tranh tại Việt Nam.',
            ],
            [
                'name' => 'NXB Trẻ',
                'address' => '161B Lý Chính Thắng, Quận 3, TP.HCM',
                'email' => 'info@nxbtre.com.vn',
                'phone' => '028-39316289',
                'website' => 'https://www.nxbtre.com.vn',
                'description' => 'Nhà xuất bản Trẻ chuyên xuất bản sách dành cho thanh thiếu niên, sách văn học và sách giáo khoa.',
            ],
            [
                'name' => 'NXB Tổng hợp TP.HCM',
                'address' => '62 Nguyễn Thị Minh Khai, Quận 1, TP.HCM',
                'email' => 'tonghop@nxbhcm.com.vn',
                'phone' => '028-38225340',
                'website' => 'https://www.nxbhcm.com.vn',
                'description' => 'Nhà xuất bản Tổng hợp TP.HCM chuyên xuất bản các loại sách về văn hóa, lịch sử, chính trị và kinh tế.',
            ],
            [
                'name' => 'NXB Lao Động',
                'address' => '175 Giảng Võ, Hà Nội',
                'email' => 'nxblaodong@vnn.vn',
                'phone' => '024-38515380',
                'website' => 'https://www.nxblaodong.com.vn',
                'description' => 'Nhà xuất bản Lao Động chuyên xuất bản sách về lao động, việc làm và các vấn đề xã hội.',
            ],
            [
                'name' => 'NXB Đại học Quốc gia Hà Nội',
                'address' => '16 Hàng Chuối, Hà Nội',
                'email' => 'nxb@vnu.edu.vn',
                'phone' => '024-39714896',
                'website' => 'https://press.vnu.edu.vn',
                'description' => 'Nhà xuất bản Đại học Quốc gia Hà Nội chuyên xuất bản sách giáo trình, tài liệu học tập và nghiên cứu khoa học.',
            ],
            [
                'name' => 'NXB Chính trị Quốc gia Sự thật',
                'address' => '6/86 Duy Tân, Cầu Giấy, Hà Nội',
                'email' => 'nxbctqg@gmail.com',
                'phone' => '024-37470780',
                'website' => 'https://www.nxbctqg.org.vn',
                'description' => 'Nhà xuất bản Chính trị Quốc gia Sự thật chuyên xuất bản sách về chính trị, pháp luật và lịch sử.',
            ],
            [
                'name' => 'NXB Phụ nữ Việt Nam',
                'address' => '39 Hàng Chuối, Hà Nội',
                'email' => 'info@nxbphunu.com.vn',
                'phone' => '024-39710717',
                'website' => 'https://www.nxbphunu.com.vn',
                'description' => 'Nhà xuất bản Phụ nữ Việt Nam chuyên xuất bản sách về phụ nữ, gia đình và xã hội.',
            ],
            [
                'name' => 'NXB Hội Nhà văn',
                'address' => '65 Nguyễn Du, Hà Nội',
                'email' => 'nxbhoinhavan@vnn.vn',
                'phone' => '024-38222135',
                'website' => 'https://www.nxbhoinhavan.org.vn',
                'description' => 'Nhà xuất bản Hội Nhà văn chuyên xuất bản sách văn học, nghệ thuật và các tác phẩm của các nhà văn Việt Nam.',
            ],
            [
                'name' => 'NXB Thanh Niên',
                'address' => '64 Bà Triệu, Hà Nội',
                'email' => 'info@nxbthanhnien.vn',
                'phone' => '024-62631704',
                'website' => 'https://www.nxbthanhnien.vn',
                'description' => 'Nhà xuất bản Thanh Niên chuyên xuất bản sách dành cho thanh niên và các tác phẩm về đề tài thanh niên.',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}

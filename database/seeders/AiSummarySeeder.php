<?php

namespace Database\Seeders;

use App\Models\AiSummary;
use App\Models\Document;
use Illuminate\Database\Seeder;

class AiSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = Document::all();

        $summaries = [
            'Tài liệu này trình bày chi tiết về Laravel 12, bao gồm các tính năng mới, cách cài đặt, và hướng dẫn cơ bản về các thành phần chính của framework. Ngoài ra còn có phần giới thiệu về các best practices và cách triển khai ứng dụng Laravel hiệu quả.',
            'Bài viết giới thiệu về Machine Learning cơ bản, bao gồm các khái niệm, thuật toán phổ biến, và ứng dụng thực tế. Tài liệu giúp người đọc hiểu rõ về cách máy học hoạt động, làm thế nào để huấn luyện mô hình, và các nguyên tắc cơ bản khi xây dựng hệ thống AI.',
            'Tài liệu về kinh tế vĩ mô cung cấp kiến thức cơ bản về các nguyên lý kinh tế, chính sách tiền tệ, thị trường lao động, và tăng trưởng kinh tế. Đặc biệt tài liệu phân tích chi tiết về tác động của các chính sách kinh tế vĩ mô đến nền kinh tế quốc gia.',
            'Cuốn sách này trình bày các phương pháp quản trị doanh nghiệp hiện đại, từ chiến lược kinh doanh, quản lý nhân sự đến tài chính và marketing. Nội dung giúp các nhà quản lý phát triển kỹ năng lãnh đạo và đưa ra quyết định hiệu quả trong môi trường kinh doanh.',
            'Tài liệu dược lý học trình bày chi tiết về cơ chế tác dụng của thuốc, tương tác thuốc, và ứng dụng lâm sàng. Nội dung bao gồm các nhóm thuốc chính, cách dùng thuốc an toàn, và phương pháp theo dõi hiệu quả điều trị.',
            'Tài liệu này giới thiệu các phương pháp giảng dạy hiện đại, tương tác và hiệu quả. Nội dung bao gồm kỹ thuật thiết kế bài giảng, phương pháp đánh giá, và cách tạo môi trường học tập tích cực. Đặc biệt chú trọng vào việc áp dụng công nghệ trong giảng dạy.',
            'Tài liệu luật doanh nghiệp 2023 cung cấp thông tin chi tiết về các quy định mới nhất, thủ tục thành lập doanh nghiệp, quyền và nghĩa vụ của doanh nghiệp, và các vấn đề pháp lý liên quan đến hoạt động kinh doanh tại Việt Nam.',
            'Tài liệu vật lý lượng tử trình bày các nguyên lý cơ bản, từ cơ học lượng tử, nguyên lý bất định Heisenberg đến các ứng dụng trong công nghệ hiện đại. Nội dung phù hợp cho sinh viên ngành vật lý và các ngành khoa học liên quan.',
            'Hướng dẫn thiết kế đồ họa với Adobe Photoshop bao gồm các kỹ thuật cơ bản và nâng cao, từ chỉnh sửa ảnh, thiết kế banner đến xử lý đồ họa chuyên nghiệp. Tài liệu cung cấp nhiều ví dụ thực hành và mẹo hữu ích.',
            'Tài liệu tiếng Anh giao tiếp cơ bản giúp người học nắm vững các cấu trúc ngữ pháp cơ bản, từ vựng thông dụng, và cách phát âm chuẩn. Nội dung bao gồm các tình huống giao tiếp thường gặp và bài tập thực hành.',
        ];

        for ($i = 0; $i < 10; $i++) {
            AiSummary::create([
                'document_id' => $documents->random()->id,
                'summary' => $summaries[$i],
            ]);
        }
    }
}

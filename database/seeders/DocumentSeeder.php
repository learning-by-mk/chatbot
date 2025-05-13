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

        $documents = [
            [
                'title' => 'Hướng dẫn Laravel 12',
                'description' => 'Tài liệu hướng dẫn chi tiết Laravel 12 cho người mới bắt đầu',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Hướng dẫn Laravel 12</h1>
                <p>Tài liệu hướng dẫn chi tiết Laravel 12 cho người mới bắt đầu. Laravel là một PHP framework mạnh mẽ với cú pháp tinh tế và dễ đọc.</p>
                <h2>Cài đặt Laravel 12</h2>
                <p>Để cài đặt Laravel 12, bạn cần:</p>
                <ul>
                    <li>PHP 8.3 trở lên</li>
                    <li>Composer</li>
                    <li>Các extension PHP cần thiết</li>
                </ul>
                <p>Sử dụng lệnh: <code>composer create-project laravel/laravel example-app</code></p>
                <h2>Tính năng mới</h2>
                <p>Laravel 12 mang đến nhiều cải tiến về hiệu suất và bảo mật, cùng với cú pháp ngắn gọn hơn và khả năng mở rộng tốt hơn.</p>',
            ],
            [
                'title' => 'Cơ bản về Machine Learning',
                'description' => 'Giới thiệu cơ bản về học máy và các ứng dụng phổ biến',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Cơ bản về Machine Learning</h1>
                <p>Giới thiệu cơ bản về học máy và các ứng dụng phổ biến trong cuộc sống hàng ngày.</p>
                <h2>Khái niệm Machine Learning</h2>
                <p>Machine Learning (Học máy) là một nhánh của trí tuệ nhân tạo (AI) cho phép hệ thống học hỏi từ dữ liệu, xác định mẫu và đưa ra quyết định với sự can thiệp tối thiểu của con người.</p>
                <h2>Các loại Machine Learning</h2>
                <ul>
                    <li><strong>Supervised Learning:</strong> Học có giám sát, sử dụng dữ liệu đã được gán nhãn</li>
                    <li><strong>Unsupervised Learning:</strong> Học không giám sát, tìm kiếm mẫu trong dữ liệu không gán nhãn</li>
                    <li><strong>Reinforcement Learning:</strong> Học tăng cường, học từ phản hồi môi trường</li>
                </ul>
                <p>Các ứng dụng phổ biến bao gồm nhận diện hình ảnh, xử lý ngôn ngữ tự nhiên và hệ thống đề xuất.</p>',
            ],
            [
                'title' => 'Kinh tế vĩ mô',
                'description' => 'Tài liệu giảng dạy về kinh tế vĩ mô',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Kinh tế vĩ mô</h1>
                <p>Tài liệu giảng dạy về kinh tế vĩ mô, bao gồm các nguyên lý cơ bản và ứng dụng thực tế.</p>
                <h2>Các khái niệm cơ bản</h2>
                <p>Kinh tế vĩ mô nghiên cứu về nền kinh tế như một tổng thể, phân tích các hiện tượng như lạm phát, thất nghiệp, tăng trưởng kinh tế và chính sách kinh tế của chính phủ.</p>
                <h2>Tổng cung và tổng cầu</h2>
                <p>Mô hình tổng cung-tổng cầu là công cụ phân tích cơ bản trong kinh tế vĩ mô, giúp giải thích sự biến động của giá cả và sản lượng trong nền kinh tế.</p>
                <h2>Chính sách tài khóa và tiền tệ</h2>
                <p>Các chính sách tài khóa và tiền tệ là công cụ chính mà chính phủ sử dụng để điều tiết nền kinh tế, kiểm soát lạm phát và thúc đẩy tăng trưởng kinh tế.</p>',
            ],
            [
                'title' => 'Quản trị doanh nghiệp',
                'description' => 'Các phương pháp quản trị doanh nghiệp hiệu quả',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Quản trị doanh nghiệp</h1>
                <p>Các phương pháp quản trị doanh nghiệp hiệu quả trong thời đại số.</p>
                <h2>Nguyên tắc quản trị hiện đại</h2>
                <p>Quản trị doanh nghiệp hiện đại đặt trọng tâm vào việc phát triển con người, tạo văn hóa doanh nghiệp tích cực và áp dụng công nghệ số vào quy trình quản lý.</p>
                <h2>Chiến lược và kế hoạch</h2>
                <p>Xây dựng chiến lược kinh doanh rõ ràng, đặt mục tiêu SMART (Specific, Measurable, Achievable, Relevant, Time-bound) và triển khai kế hoạch hành động chi tiết.</p>
                <h2>Quản lý nhân sự</h2>
                <p>Tuyển dụng đúng người, đào tạo phát triển nhân viên, xây dựng chính sách đãi ngộ hợp lý và tạo môi trường làm việc tích cực là chìa khóa thành công của doanh nghiệp.</p>
                <h2>Quản trị tài chính</h2>
                <p>Kiểm soát dòng tiền, tối ưu hóa chi phí, phân bổ nguồn lực hợp lý và đảm bảo minh bạch trong báo cáo tài chính.</p>',
            ],
            [
                'title' => 'Dược lý học đại cương',
                'description' => 'Tài liệu dược lý học cho sinh viên y dược',
                'status' => 'pending',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Dược lý học đại cương</h1>
                <p>Tài liệu dược lý học toàn diện dành cho sinh viên y dược.</p>
                <h2>Khái niệm cơ bản</h2>
                <p>Dược lý học là khoa học nghiên cứu về tác động của thuốc lên cơ thể sống, bao gồm cơ chế tác dụng, hấp thu, phân bố, chuyển hóa và thải trừ thuốc.</p>
                <h2>Dược động học</h2>
                <p>Dược động học nghiên cứu quá trình thuốc di chuyển trong cơ thể, từ lúc hấp thu cho đến khi thải trừ. Các thông số quan trọng bao gồm nồng độ đỉnh, thời gian bán thải và sinh khả dụng.</p>
                <h2>Dược lực học</h2>
                <p>Dược lực học nghiên cứu cơ chế tác dụng của thuốc tại nơi đích, bao gồm tương tác với thụ thể, enzym và kênh ion.</p>
                <h2>Tương tác thuốc</h2>
                <p>Tương tác thuốc có thể là hiệp đồng, đối kháng hoặc trung hòa, có thể ảnh hưởng đến hiệu quả và độ an toàn của điều trị.</p>',
            ],
            [
                'title' => 'Phương pháp giảng dạy hiệu quả',
                'description' => 'Các phương pháp giảng dạy hiện đại và hiệu quả',
                'status' => 'approved',
                'author_id' => $users->random()->id,
                'uploaded_by_id' => $users->random()->id,
                'content' => '<h1>Phương pháp giảng dạy hiệu quả</h1>
                <p>Các phương pháp giảng dạy hiện đại và hiệu quả trong giáo dục.</p>
                <h2>Giảng dạy tích cực</h2>
                <p>Phương pháp giảng dạy tích cực đặt học sinh vào vị trí trung tâm, khuyến khích sự tham gia và tương tác trong lớp học.</p>
                <h2>Học dựa trên dự án</h2>
                <p>Project-based learning (PBL) giúp học sinh áp dụng kiến thức vào thực tế thông qua việc thực hiện các dự án, giải quyết vấn đề thực tế.</p>
                <h2>Ứng dụng công nghệ trong giảng dạy</h2>
                <p>Các công cụ e-learning, học liệu số và ứng dụng công nghệ thực tế ảo/thực tế tăng cường (VR/AR) giúp tăng cường trải nghiệm học tập.</p>
                <h2>Đánh giá đa chiều</h2>
                <p>Sử dụng nhiều phương pháp đánh giá khác nhau như bài tập, dự án, thuyết trình và portfolio để đánh giá toàn diện năng lực của học sinh.</p>',
            ],
            [
                'title' => 'Luật Doanh nghiệp 2023',
                'description' => 'Cập nhật luật doanh nghiệp mới nhất',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Luật Doanh nghiệp 2023</h1>
                <p>Cập nhật luật doanh nghiệp mới nhất và những thay đổi quan trọng.</p>
                <h2>Những thay đổi chính</h2>
                <p>Luật Doanh nghiệp 2023 mang đến nhiều thay đổi quan trọng về thành lập doanh nghiệp, quản trị công ty và bảo vệ cổ đông.</p>
                <h2>Thủ tục đăng ký kinh doanh</h2>
                <p>Các thủ tục đăng ký kinh doanh được đơn giản hóa, tạo điều kiện thuận lợi cho việc thành lập doanh nghiệp mới.</p>
                <h2>Quản trị doanh nghiệp</h2>
                <p>Quy định về quản trị doanh nghiệp được tăng cường, đặc biệt là các quy định về trách nhiệm của người quản lý doanh nghiệp, bảo vệ cổ đông thiểu số và minh bạch thông tin.</p>
                <h2>Chuyển đổi loại hình doanh nghiệp</h2>
                <p>Các quy định mới về chuyển đổi loại hình doanh nghiệp, sáp nhập, hợp nhất và tái cấu trúc doanh nghiệp.</p>',
            ],
            [
                'title' => 'Vật lý lượng tử cơ bản',
                'description' => 'Tài liệu về vật lý lượng tử cho sinh viên',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Vật lý lượng tử cơ bản</h1>
                <p>Tài liệu về vật lý lượng tử dành cho sinh viên ngành vật lý và các ngành liên quan.</p>
                <h2>Lý thuyết cơ bản</h2>
                <p>Vật lý lượng tử là nền tảng của vật lý hiện đại, giải thích hành vi của vật chất và năng lượng ở cấp độ hạt nhân, nguyên tử và hạ nguyên tử.</p>
                <h2>Lưỡng tính sóng-hạt</h2>
                <p>Các hạt vật chất như electron, photon thể hiện cả tính chất sóng và tính chất hạt, phụ thuộc vào cách quan sát và đo lường.</p>
                <h2>Nguyên lý bất định Heisenberg</h2>
                <p>Nguyên lý bất định khẳng định rằng không thể đồng thời xác định chính xác vị trí và động lượng của một hạt, thể hiện giới hạn cơ bản trong việc đo lường.</p>
                <h2>Phương trình Schrödinger</h2>
                <p>Phương trình Schrödinger mô tả hàm sóng của một hệ lượng tử, từ đó xác định xác suất tìm thấy hạt tại một vị trí nhất định.</p>',
            ],
            [
                'title' => 'Thiết kế đồ họa với Adobe Photoshop',
                'description' => 'Hướng dẫn thiết kế đồ họa chuyên nghiệp',
                'status' => 'pending',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Thiết kế đồ họa với Adobe Photoshop</h1>
                <p>Hướng dẫn thiết kế đồ họa chuyên nghiệp sử dụng phần mềm Adobe Photoshop.</p>
                <h2>Giao diện và công cụ cơ bản</h2>
                <p>Tìm hiểu về giao diện Photoshop, các công cụ chỉnh sửa ảnh cơ bản, hệ thống layer và các panel.</p>
                <h2>Chỉnh sửa ảnh</h2>
                <p>Các kỹ thuật chỉnh sửa ảnh bao gồm điều chỉnh màu sắc, độ sáng, tương phản, cắt ghép ảnh và xóa phông nền.</p>
                <h2>Thiết kế banner và poster</h2>
                <p>Hướng dẫn thiết kế banner quảng cáo và poster ấn tượng với các nguyên tắc bố cục, sử dụng màu sắc và typography.</p>
                <h2>Hiệu ứng và bộ lọc</h2>
                <p>Khám phá các hiệu ứng và bộ lọc sáng tạo trong Photoshop để tạo ra những thiết kế độc đáo và thu hút.</p>
                <h2>Tối ưu hóa cho web và in ấn</h2>
                <p>Cách lưu file và tối ưu hóa thiết kế cho web và in ấn với các định dạng và độ phân giải phù hợp.</p>',
            ],
            [
                'title' => 'Tiếng Anh giao tiếp cơ bản',
                'description' => 'Tài liệu học tiếng Anh giao tiếp cho người mới',
                'status' => 'approved',
                'uploaded_by_id' => $users->random()->id,
                'author_id' => $users->random()->id,
                'content' => '<h1>Tiếng Anh giao tiếp cơ bản</h1>
                <p>Tài liệu học tiếng Anh giao tiếp dành cho người mới bắt đầu.</p>
                <h2>Chào hỏi và giới thiệu</h2>
                <p>Các mẫu câu và từ vựng cơ bản để chào hỏi, giới thiệu bản thân và làm quen với người khác trong các tình huống xã hội và công việc.</p>
                <h2>Giao tiếp hàng ngày</h2>
                <p>Từ vựng và mẫu câu cho các tình huống giao tiếp hàng ngày như mua sắm, đi nhà hàng, đặt phòng khách sạn và hỏi đường.</p>
                <h2>Ngữ pháp cơ bản</h2>
                <p>Các điểm ngữ pháp cơ bản như thì hiện tại đơn, hiện tại tiếp diễn, quá khứ đơn và cấu trúc câu hỏi.</p>
                <h2>Luyện nghe và phát âm</h2>
                <p>Các bài tập luyện nghe và hướng dẫn phát âm chuẩn với những mẹo giúp cải thiện kỹ năng giao tiếp.</p>
                <h2>Từ vựng theo chủ đề</h2>
                <p>Từ vựng được phân loại theo các chủ đề phổ biến như gia đình, công việc, du lịch, sức khỏe và giải trí.</p>',
            ],
        ];

        foreach ($documents as $index => $document) {
            Document::updateOrCreate([
                'title' => $document['title'],
            ], $document);
        }
    }
}

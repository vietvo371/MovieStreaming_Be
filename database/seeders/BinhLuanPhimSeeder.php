<?php

namespace Database\Seeders;

use App\Models\BinhLuanPhim;
use App\Models\KhachHang;
use App\Models\Phim;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BinhLuanPhimSeeder extends Seeder
{
    private $reviewTemplates = [
        5 => [
            "Phim tuyệt vời! Cốt truyện cuốn hút từ đầu đến cuối.",
            "Diễn xuất của dàn cast quá xuất sắc, không thể chê vào đâu được.",
            "Một kiệt tác điện ảnh, xứng đáng 5 sao.",
            "Phim hay nhất mình xem trong năm nay!",
            "Kỹ xảo đỉnh cao, âm nhạc tuyệt vời, diễn xuất xuất thần.",
        ],
        4 => [
            "Phim rất hay, chỉ tiếc vài chi tiết nhỏ chưa được hoàn thiện.",
            "Diễn xuất tốt, cốt truyện hấp dẫn.",
            "Phim đáng xem, có vài điểm nhỏ có thể cải thiện.",
            "Khá ấn tượng với nội dung và cách dẫn truyện.",
            "Phim hay, đáng để xem lại nhiều lần.",
        ],
        3 => [
            "Phim ở mức khá, còn nhiều điểm có thể phát triển thêm.",
            "Nội dung tạm được, diễn xuất chưa thực sự tốt.",
            "Cốt truyện bình thường, không có gì đặc sắc.",
            "Xem được, không quá xuất sắc nhưng cũng không tệ.",
            "Phim có tiềm năng nhưng chưa khai thác hết.",
        ],
        2 => [
            "Phim khá nhàm chán, cốt truyện lặp lại.",
            "Diễn xuất chưa tốt, kỹ xảo sơ sài.",
            "Nội dung không hấp dẫn, thiếu sức cuốn hút.",
            "Phim không như kỳ vọng, nhiều điểm chưa hợp lý.",
            "Kịch bản yếu, diễn biến chậm.",
        ],
        1 => [
            "Phim rất tệ, thời gian xem phí phạm.",
            "Kịch bản rời rạc, diễn xuất kém.",
            "Không hiểu sao phim lại được sản xuất.",
            "Thất vọng hoàn toàn với chất lượng phim.",
            "Phim thiếu đầu tư, không đáng để xem.",
        ],
    ];

    public function run()
    {
        $phims = Phim::where('tinh_trang', 1)->get();
        $khachHangs = KhachHang::all();
        $now = Carbon::now();

        // Tạo 500 bình luận ngẫu nhiên
        for ($i = 0; $i < 500; $i++) {
            $soSao = rand(1, 5);
            $phim = $phims->random();
            $khachHang = $khachHangs->random();

            // Tạo ngày ngẫu nhiên trong 6 tháng gần đây
            $randomDate = Carbon::now()->subDays(rand(0, 180));

            // Lấy ngẫu nhiên một bình luận từ mảng template theo số sao
            $noiDung = $this->reviewTemplates[$soSao][array_rand($this->reviewTemplates[$soSao])];

            BinhLuanPhim::create([
                'noi_dung' => $noiDung,
                'id_phim' => $phim->id,
                'id_khach_hang' => $khachHang->id,
                'so_sao' => $soSao,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }

        // Tạo thêm một số bình luận tập trung cho một số phim cụ thể để tạo top phim
        $topPhims = $phims->random(5);
        foreach ($topPhims as $phim) {
            // Tạo 20-30 bình luận tốt cho mỗi phim top
            $numReviews = rand(20, 30);
            for ($i = 0; $i < $numReviews; $i++) {
                $soSao = rand(4, 5); // Chỉ tạo đánh giá cao
                $khachHang = $khachHangs->random();
                $randomDate = Carbon::now()->subDays(rand(0, 90));

                BinhLuanPhim::create([
                    'noi_dung' => $this->reviewTemplates[$soSao][array_rand($this->reviewTemplates[$soSao])],
                    'id_phim' => $phim->id,
                    'id_khach_hang' => $khachHang->id,
                    'so_sao' => $soSao,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}

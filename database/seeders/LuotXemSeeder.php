<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use App\Models\LuotPhim;
use App\Models\Phim;
use App\Models\TapPhim;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LuotXemSeeder extends Seeder
{
    private $viewPatterns = [
        'weekday' => [
            'morning' => [8, 12],    // Giờ sáng: ít người xem
            'afternoon' => [13, 17], // Giờ chiều: lượng trung bình
            'evening' => [18, 23],   // Giờ tối: đông người xem
        ],
        'weekend' => [
            'morning' => [9, 12],    // Cuối tuần giờ sáng: lượng trung bình
            'afternoon' => [13, 17], // Cuối tuần giờ chiều: đông
            'evening' => [18, 24],   // Cuối tuần giờ tối: rất đông
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phims = Phim::where('tinh_trang', 1)->get();
        $users = KhachHang::all();

        // Tạo một số phim "hot" với nhiều lượt xem
        $hotPhims = $phims->random(5);

        // Tạo dữ liệu cho 180 ngày (6 tháng) gần đây
        for ($day = 0; $day < 180; $day++) {
            $date = Carbon::now()->subDays($day);
            $isWeekend = $date->isWeekend();

            foreach ($phims as $phim) {
                $tapPhims = TapPhim::where('id_phim', $phim->id)->get();
                if ($tapPhims->isEmpty()) continue;

                // Xác định số lượng người xem dựa vào loại phim và thời gian
                $isHotMovie = $hotPhims->contains($phim);

                foreach ($tapPhims as $tap) {
                    // Buổi sáng
                    $this->createViewsForTimeSlot(
                        $isWeekend ? 'weekend' : 'weekday',
                        'morning',
                        $date,
                        $phim,
                        $tap,
                        $users,
                        $isHotMovie
                    );

                    // Buổi chiều
                    $this->createViewsForTimeSlot(
                        $isWeekend ? 'weekend' : 'weekday',
                        'afternoon',
                        $date,
                        $phim,
                        $tap,
                        $users,
                        $isHotMovie
                    );

                    // Buổi tối
                    $this->createViewsForTimeSlot(
                        $isWeekend ? 'weekend' : 'weekday',
                        'evening',
                        $date,
                        $phim,
                        $tap,
                        $users,
                        $isHotMovie
                    );
                }

                // Cập nhật tổng lượt xem cho phim
                $totalViews = LuotPhim::where('id_phim', $phim->id)->sum('so_luot_xem');
                $phim->update(['tong_luot_xem' => $totalViews]);
            }
        }
    }

    private function createViewsForTimeSlot($dayType, $timeSlot, $date, $phim, $tap, $users, $isHotMovie)
    {
        $timeRange = $this->viewPatterns[$dayType][$timeSlot];
        $hour = rand($timeRange[0], $timeRange[1]);
        $date = $date->copy()->setHour($hour)->setMinute(rand(0, 59));

        // Xác định số lượng người xem dựa vào các yếu tố
        $baseViewers = match($timeSlot) {
            'morning' => rand(1, 5),
            'afternoon' => rand(3, 8),
            'evening' => rand(5, 15),
        };

        // Tăng lượt xem cho phim hot và cuối tuần
        if ($isHotMovie) $baseViewers *= 2;
        if ($dayType === 'weekend') $baseViewers = (int)($baseViewers * 1.5);

        // Giảm lượt xem cho các tập sau
        $episodeNumber = $tap->so_tap;
        $dropoffFactor = 1 - (min($episodeNumber, 10) * 0.05); // Giảm 5% mỗi tập, tối đa 50%
        $baseViewers = max(1, (int)($baseViewers * $dropoffFactor));

        // Chọn ngẫu nhiên người xem
        $randomUsers = $users->random(min($baseViewers, $users->count()));

        foreach ($randomUsers as $user) {
            LuotPhim::create([
                'id_phim' => $phim->id,
                'id_tap_phim' => $tap->id,
                'id_khach_hang' => $user->id,
                'ngay_xem' => $date,
                'so_luot_xem' => rand(1, 3), // Số lần xem lại trong cùng khung giờ
            ]);
        }
    }
}

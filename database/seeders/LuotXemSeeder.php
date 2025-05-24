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
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách phim, tập phim và users để tạo dữ liệu
        $phims = Phim::all();
        $users = KhachHang::all();

        // Tạo lượt xem cho 30 ngày gần đây
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            foreach ($phims as $phim) {
                // Lấy danh sách tập của phim
                $tapPhims = TapPhim::where('id_phim', $phim->id)->get();

                foreach ($tapPhims as $tap) {
                    // Random số lượng user xem tập này trong ngày (1-20 users)
                    $viewersCount = rand(1, 10);
                    $randomUsers = $users->random($viewersCount);

                    foreach ($randomUsers as $user) {
                        // Tạo lượt xem với số lượt xem ngẫu nhiên (1-5 lần/ngày/user)
                        LuotPhim::create([
                            'id_phim' => $phim->id,
                            'id_tap_phim' => $tap->id,
                            'id_khach_hang' => $user->id,
                            'ngay_xem' => $date,
                            'so_luot_xem' => rand(1, 5)
                        ]);
                    }

                    // Cập nhật tổng lượt xem cho phim
                    $totalViews = LuotPhim::where('id_phim', $phim->id)->sum('so_luot_xem');
                    $phim->update(['tong_luot_xem' => $totalViews]);
                }
            }
        }
    }
}

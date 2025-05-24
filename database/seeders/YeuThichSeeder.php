<?php

namespace Database\Seeders;

use App\Models\KhachHang;
use App\Models\Phim;
use App\Models\YeuThich;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class YeuThichSeeder extends Seeder
{
    public function run()
    {
        $phims = Phim::where('tinh_trang', 1)->get();
        $khachHangs = KhachHang::all();

        // Tạo một số phim "hot" với nhiều lượt thích
        $hotPhims = $phims->random(5);
        foreach ($hotPhims as $phim) {
            // 40-60% số khách hàng sẽ thích những phim hot
            $numLikes = (int) ($khachHangs->count() * (rand(40, 60) / 100));

            // Lấy ngẫu nhiên danh sách khách hàng
            $randomKhachHangs = $khachHangs->random($numLikes);

            foreach ($randomKhachHangs as $khachHang) {
                // Tạo thời gian yêu thích ngẫu nhiên trong 3 tháng gần đây
                $randomDate = Carbon::now()->subDays(rand(0, 90));

                YeuThich::create([
                    'id_phim' => $phim->id,
                    'id_khach_hang' => $khachHang->id,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }

        // Tạo lượt thích ngẫu nhiên cho các phim còn lại
        $remainingPhims = $phims->diff($hotPhims);
        foreach ($remainingPhims as $phim) {
            // 5-20% số khách hàng sẽ thích những phim thông thường
            $numLikes = (int) ($khachHangs->count() * (rand(5, 20) / 100));

            // Lấy ngẫu nhiên danh sách khách hàng
            $randomKhachHangs = $khachHangs->random($numLikes);

            foreach ($randomKhachHangs as $khachHang) {
                // Tạo thời gian yêu thích ngẫu nhiên trong 6 tháng gần đây
                $randomDate = Carbon::now()->subDays(rand(0, 180));

                YeuThich::create([
                    'id_phim' => $phim->id,
                    'id_khach_hang' => $khachHang->id,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}

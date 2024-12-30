<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LuotXemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('luot_xems')->truncate();

        $luotXems = [
            [
                'id_phim' => 1,
                'id_tap_phim' => 1,
                'ngay_xem' => Carbon::now()->subDays(10),
                'so_luot_xem' => 100,
                'id_khach_hang' => 1,
            ],
            [
                'id_phim' => 2,
                'id_tap_phim' => 2,
                'ngay_xem' => Carbon::now()->subDays(9),
                'so_luot_xem' => 150,
                'id_khach_hang' => 2,
            ],
            [
                'id_phim' => 3,
                'id_tap_phim' => 3,
                'ngay_xem' => Carbon::now()->subDays(8),
                'so_luot_xem' => 200,
                'id_khach_hang' => 3,
            ],
            [
                'id_phim' => 4,
                'id_tap_phim' => 1,
                'ngay_xem' => Carbon::now()->subDays(7),
                'so_luot_xem' => 50,
                'id_khach_hang' => 4,
            ],
            [
                'id_phim' => 5,
                'id_tap_phim' => 2,
                'ngay_xem' => Carbon::now()->subDays(6),
                'so_luot_xem' => 120,
                'id_khach_hang' => 5,
            ],
            [
                'id_phim' => 1,
                'id_tap_phim' => 3,
                'ngay_xem' => Carbon::now()->subDays(5),
                'so_luot_xem' => 300,
                'id_khach_hang' => 6,
            ],
            [
                'id_phim' => 2,
                'id_tap_phim' => 1,
                'ngay_xem' => Carbon::now()->subDays(4),
                'so_luot_xem' => 180,
                'id_khach_hang' => 7,
            ],
            [
                'id_phim' => 3,
                'id_tap_phim' => 2,
                'ngay_xem' => Carbon::now()->subDays(3),
                'so_luot_xem' => 90,
                'id_khach_hang' => 8,
            ],
            [
                'id_phim' => 4,
                'id_tap_phim' => 3,
                'ngay_xem' => Carbon::now()->subDays(2),
                'so_luot_xem' => 110,
                'id_khach_hang' => 9,
            ],
            [
                'id_phim' => 5,
                'id_tap_phim' => 1,
                'ngay_xem' => Carbon::now()->subDays(1),
                'so_luot_xem' => 250,
                'id_khach_hang' => 10,
            ],
        ];

        DB::table('luot_xems')->insert($luotXems);
    }
}

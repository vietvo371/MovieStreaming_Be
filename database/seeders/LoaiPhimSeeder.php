<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaiPhimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loai_phims')->delete();
        DB::table('loai_phims')->truncate();
        DB::table('loai_phims')->insert([
            ['ten_loai_phim' => 'Phim Lẻ', 'slug_loai_phim' => 'phim-le', 'tinh_trang' => '1'],
            ['ten_loai_phim' => 'Phim Chiếu Rap', 'slug_loai_phim' => 'phim-chieu-rap', 'tinh_trang' => '1'],
            ['ten_loai_phim' => 'Phim Bộ', 'slug_loai_phim' => 'phim-bo', 'tinh_trang' => '1'],
        ]);


    }
}

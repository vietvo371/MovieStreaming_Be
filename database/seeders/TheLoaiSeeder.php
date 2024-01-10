<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TheLoaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('the_loais')->delete();
        DB::table('the_loais')->truncate();
        DB::table('the_loais')->insert([
            ['ten_the_loai' => 'Hành Động', 'slug_the_loai' => 'hanh-dong', 'tinh_trang' => '1'],
            ['ten_the_loai' => 'Viễn Tưởng', 'slug_the_loai' => 'vien-tuong', 'tinh_trang' => '1'],
            ['ten_the_loai' => 'Chuyển Sinh', 'slug_the_loai' => 'chuyen-sinh', 'tinh_trang' => '1'],
            ['ten_the_loai' => 'Pháp Thuật', 'slug_the_loai' => 'phap-thuat', 'tinh_trang' => '1'],
            ['ten_the_loai' => 'Siêu Nhiên', 'slug_the_loai' => 'sieu-nhien', 'tinh_trang' => '1'],
            ['ten_the_loai' => 'Phiêu Lưu', 'slug_the_loai' => 'phieu-luu', 'tinh_trang' => '1'],
        ]);
    }
}

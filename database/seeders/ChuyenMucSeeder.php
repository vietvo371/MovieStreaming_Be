<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChuyenMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chuyen_mucs')->delete();
        DB::table('chuyen_mucs')->truncate();
        DB::table('chuyen_mucs')->insert([
            ['ten_chuyen_muc' => 'Tin Tức', 'slug_chuyen_muc' => 'tin-tuc', 'tinh_trang' => '1'],
            ['ten_chuyen_muc' => 'Phim Sắp Chiếu', 'slug_chuyen_muc' => 'phim-sap-chieu', 'tinh_trang' => '1'],
        ]);
    }
}

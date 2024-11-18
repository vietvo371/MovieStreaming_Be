<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoiVipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('goi_vips')->truncate();

        $goiVips = [
            [
                'ten_goi' => 'Gói 3 Tháng',
                'slug_goi_vip' => 'goi-3-thang',
                'thoi_han' => 3, // Duration in months
                'tien_goc' => 10000, // Original price
                'tien_sale' => 4000, // Discounted price
                'tinh_trang' => 1, // Active status
            ],
            [
                'ten_goi' => 'Gói 6 Tháng',
                'slug_goi_vip' => 'goi-6-thang',
                'thoi_han' => 9, // Duration in months
                'tien_goc' => 9000, // Original price
                'tien_sale' => 5000, // Discounted price
                'tinh_trang' => 1, // Active status
            ],
            [
                'ten_goi' => 'Gói 9 Tháng',
                'slug_goi_vip' => 'goi-9-thang',
                'thoi_han' => 12, // Duration in months
                'tien_goc' => 12000, // Original price
                'tien_sale' => 6000, // Discounted price
                'tinh_trang' => 1, // Active status
            ],
        ];

        DB::table('goi_vips')->insert($goiVips);
    }
}

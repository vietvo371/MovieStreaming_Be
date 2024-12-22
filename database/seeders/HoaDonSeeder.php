<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoaDonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hoa_dons')->truncate();

        DB::table('hoa_dons')->insert([
            [
                'ma_hoa_don' => 'HD001',
                'id_goi' => 1, // Gói 1 Tháng
                'id_khach_hang' => 1,
                'tong_tien' => 20000, // tien_sale from goi 1 thang
                'so_tien_da_thanh_toan' => 20000,
                'tinh_trang' => 1, // Đã thanh toán
                'ngay_bat_dau' => Carbon::now()->subDays(10),
                'ngay_ket_thuc' => Carbon::now()->subDays(10)->addMonths(1),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'ma_hoa_don' => 'HD002',
                'id_goi' => 2, // Gói 3 Tháng
                'id_khach_hang' => 2,
                'tong_tien' => 60000, // tien_sale from goi 3 thang
                'so_tien_da_thanh_toan' => 60000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(8),
                'ngay_ket_thuc' => Carbon::now()->subDays(8)->addMonths(3),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'ma_hoa_don' => 'HD003',
                'id_goi' => 3, // Gói 6 Tháng
                'id_khach_hang' => 3,
                'tong_tien' => 120000, // tien_sale from goi 6 thang
                'so_tien_da_thanh_toan' => 120000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(5),
                'ngay_ket_thuc' => Carbon::now()->subDays(5)->addMonths(6),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'ma_hoa_don' => 'HD004',
                'id_goi' => 1, // Gói 1 Tháng
                'id_khach_hang' => 4,
                'tong_tien' => 20000,
                'so_tien_da_thanh_toan' => 20000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(3),
                'ngay_ket_thuc' => Carbon::now()->subDays(3)->addMonths(1),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'ma_hoa_don' => 'HD004',
                'id_goi' => 1,
                'id_khach_hang' => 4,
                'tong_tien' => 20000,
                'so_tien_da_thanh_toan' => 20000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(4),
                'ngay_ket_thuc' => Carbon::now()->subDays(4)->addMonths(1),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'ma_hoa_don' => 'HD005',
                'id_goi' => 2,
                'id_khach_hang' => 5,
                'tong_tien' => 60000,
                'so_tien_da_thanh_toan' => 60000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(3),
                'ngay_ket_thuc' => Carbon::now()->subDays(3)->addMonths(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'ma_hoa_don' => 'HD006',
                'id_goi' => 3,
                'id_khach_hang' => 6,
                'tong_tien' => 120000,
                'so_tien_da_thanh_toan' => 120000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(2),
                'ngay_ket_thuc' => Carbon::now()->subDays(2)->addMonths(6),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'ma_hoa_don' => 'HD007',
                'id_goi' => 1,
                'id_khach_hang' => 7,
                'tong_tien' => 20000,
                'so_tien_da_thanh_toan' => 20000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(1),
                'ngay_ket_thuc' => Carbon::now()->subDays(1)->addMonths(1),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'ma_hoa_don' => 'HD008',
                'id_goi' => 2,
                'id_khach_hang' => 8,
                'tong_tien' => 60000,
                'so_tien_da_thanh_toan' => 60000,
                'tinh_trang' => 1,
                'ngay_bat_dau' => Carbon::now(),
                'ngay_ket_thuc' => Carbon::now()->addMonths(3),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}

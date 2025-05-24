<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiaoDichSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('giao_diches')->truncate();

        DB::table('giao_diches')->insert([
            // Giao dịch cho HD001 - VNPay thành công
            [
                'id_Khach_hang' => 1,
                'ma_giao_dich' => 'HD001',
                'orderInfo' => 'HD001',
                'transactionNo' => '14398677',
                'paymentType' => 'vnpay',
                'responseCode' => '00',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            // Giao dịch cho HD002 - MoMo thành công
            [
                'id_Khach_hang' => 2,
                'ma_giao_dich' => 'HD002',
                'orderInfo' => 'HD002',
                'transactionNo' => '4102456789',
                'paymentType' => 'momo',
                'responseCode' => '0',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            // Giao dịch cho HD003 - Chuyển khoản MB Bank thành công
            [
                'id_Khach_hang' => 3,
                'ma_giao_dich' => 'HD003',
                'orderInfo' => 'HD003',
                'transactionNo' => 'FT24035123456789',
                'paymentType' => 'mbbank',
                'responseCode' => '00',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            // Giao dịch cho HD004 - VNPay thành công
            [
                'id_Khach_hang' => 4,
                'ma_giao_dich' => 'HD004',
                'orderInfo' => 'HD004',
                'transactionNo' => '14398688',
                'paymentType' => 'vnpay',
                'responseCode' => '00',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            // Giao dịch cho HD005 - Chuyển khoản một phần (thất bại - chưa đủ tiền)
            [
                'id_Khach_hang' => 5,
                'ma_giao_dich' => 'HD005',
                'orderInfo' => 'HD005',
                'transactionNo' => 'FT24035123456790',
                'paymentType' => 'mbbank',
                'responseCode' => '02', // Giao dịch không thành công - thiếu tiền
                'transactionStatus' => 'pending',
                'tinh_trang' => 'chưa thanh toán',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            // Giao dịch cho HD006 - MoMo thành công (khách hàng 5 thanh toán lại)
            [
                'id_Khach_hang' => 5,
                'ma_giao_dich' => 'HD006',
                'orderInfo' => 'HD006',
                'transactionNo' => '4102456790',
                'paymentType' => 'momo',
                'responseCode' => '0',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            // Giao dịch cho HD007 - VNPay thành công
            [
                'id_Khach_hang' => 6,
                'ma_giao_dich' => 'HD007',
                'orderInfo' => 'HD007',
                'transactionNo' => '14398699',
                'paymentType' => 'vnpay',
                'responseCode' => '00',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // Giao dịch cho HD008 - Chuyển khoản MB Bank thành công
            [
                'id_Khach_hang' => 7,
                'ma_giao_dich' => 'HD008',
                'orderInfo' => 'HD008',
                'transactionNo' => 'FT24035123456791',
                'paymentType' => 'mbbank',
                'responseCode' => '00',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            // Giao dịch cho HD009 - MoMo thành công
            [
                'id_Khach_hang' => 8,
                'ma_giao_dich' => 'HD009',
                'orderInfo' => 'HD009',
                'transactionNo' => '4102456791',
                'paymentType' => 'momo',
                'responseCode' => '0',
                'transactionStatus' => 'success',
                'tinh_trang' => 'đã thanh toán',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // HD010 - Chưa có giao dịch vì chưa thanh toán
        ]);
    }
}

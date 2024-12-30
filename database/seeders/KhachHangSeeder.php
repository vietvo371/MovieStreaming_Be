<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KhachHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('khach_hangs')->truncate();

        DB::table('khach_hangs')->insert([
            [
                'ho_va_ten' => 'Nguyen Van A',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'nguyenvana@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0123456789',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 1,
            ],
            [
                'ho_va_ten' => 'Tran Thi B',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'tranthib@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0987654321',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 2,
            ],
            [
                'ho_va_ten' => 'Le Thi C',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'lethic@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0912345678',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 3,
            ],
            [
                'ho_va_ten' => 'Pham Van D',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'phamvand@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0908765432',
                'is_block' => true,
                'is_active' => false,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 1,
            ],
            [
                'ho_va_ten' => 'Vo Thi E',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'vothie@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0709876543',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 4,
            ],
            [
                'ho_va_ten' => 'Nguyen Van F',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'nguyenvanf@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0398765432',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 2,
            ],
            [
                'ho_va_ten' => 'Bui Thi G',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'buithig@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0901234567',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 5,
            ],
            [
                'ho_va_ten' => 'Tran Van H',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'tranvanh@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0123987654',
                'is_block' => true,
                'is_active' => false,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 3,
            ],
            [
                'ho_va_ten' => 'Nguyen Thi I',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'nguyenthi@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0901122334',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 4,
            ],
            [
                'ho_va_ten' => 'Pham Van J',
                'avatar' => 'https://via.placeholder.com/100',
                'email' => 'phamvanj@example.com',
                'password' => bcrypt('password123'),
                'so_dien_thoai' => '0387654321',
                'is_block' => true,
                'is_active' => true,
                'google_id' => null,
                'hash_reset' => null,
                'hash_active' => null,
                'id_goi_vip' => 1,
            ],
        ]);
    }
}

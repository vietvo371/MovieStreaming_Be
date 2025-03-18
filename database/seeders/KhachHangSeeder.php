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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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
                'avatar' => asset('uploads/avatars/admins/default_avatar.png'),
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

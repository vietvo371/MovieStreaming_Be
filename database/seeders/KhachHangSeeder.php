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
        DB::table('khach_hangs')->delete();
        // DB::table('khach_hangs')->truncate();
        DB::table('khach_hangs')->insert([
            [
                'id'            =>   1,
                'email'         =>'vietvo371@gmail.com',
                'ho_va_ten'     =>'Văn Việt',
                'hinh_anh'      =>'',

            ],
            [
                'id'            =>   1,
                'email'         =>'dinhquy123@gmail.com',
                'ho_va_ten'     =>'Đình Quý',
                'hinh_anh'      =>'',
            ],



        ]);
    }
}

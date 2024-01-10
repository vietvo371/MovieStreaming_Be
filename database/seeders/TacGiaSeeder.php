<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TacGiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tac_gias')->delete();
        DB::table('tac_gias')->truncate();
        DB::table('tac_gias')->insert([
                    ['ten_tac_gia' => 'Shinkai Makoto', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => 'Toriyama Akira', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => 'Tabata Yūki', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => 'Fuse ', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => ' Aya Yajima', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => 'Akutami Gege', 'tinh_trang' => '1'],
                    ['ten_tac_gia' => 'Sorachi Hideaki', 'tinh_trang' => '1'],
        ]);
    }
}

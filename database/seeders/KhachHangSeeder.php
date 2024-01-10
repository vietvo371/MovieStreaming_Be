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
        DB::table('khach_hangs')->truncate();
        DB::table('khach_hangs')->insert([
            [
                'email'         =>"vietvo371@gmail.com",
                'ho_va_ten'     =>"Văn Việt",
                'password'      =>bcrypt(123456),
                'hinh_anh'      =>"https://scontent.fdad3-6.fna.fbcdn.net/v/t39.30808-6/397776463_826354595842192_5308467141072020650_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_eui2=AeHihZEVqc7_DahynX0pC1FCPaK7xizgOa09orvGLOA5re63mDainzWdF1z7xx3-i0qDysRzenuZVFdAAkar0d8m&_nc_ohc=bM4KizezoScAX_HuD6X&_nc_ht=scontent.fdad3-6.fna&oh=00_AfDr8tcMYNcYfhifOe9KnyocJ6-pbCRVSOiCtiP_wW5g2w&oe=65A2B28A",

            ],
            [
                'email'         =>"dinhquy123@gmail.com",
                'ho_va_ten'     =>"Đình Quý",
                'password'      =>bcrypt(123456),
                'hinh_anh'      =>"https://scontent.fdad3-6.fna.fbcdn.net/v/t39.30808-6/397776463_826354595842192_5308467141072020650_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_eui2=AeHihZEVqc7_DahynX0pC1FCPaK7xizgOa09orvGLOA5re63mDainzWdF1z7xx3-i0qDysRzenuZVFdAAkar0d8m&_nc_ohc=bM4KizezoScAX_HuD6X&_nc_ht=scontent.fdad3-6.fna&oh=00_AfDr8tcMYNcYfhifOe9KnyocJ6-pbCRVSOiCtiP_wW5g2w&oe=65A2B28A",
            ],



        ]);
    }
}

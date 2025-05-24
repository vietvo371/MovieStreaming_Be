<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhanQuyensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('phan_quyens')->truncate();

        // Phân quyền cho Quản Trị Website (có tất cả quyền)
        $quanTriWebsite = DB::table('chuc_vus')
            ->where('slug_chuc_vu', 'quan-tri-website')
            ->first();

        $allActions = DB::table('actions')->get();

        $phanQuyens = [];

        // Quản trị website có tất cả quyền
        foreach($allActions as $action) {
            $phanQuyens[] = [
                'id_chuc_vu' => $quanTriWebsite->id,
                'id_chuc_nang' => $action->id,
            ];
        }

        // Quản trị Phim có quyền liên quan đến phim
        $quanTriPhim = DB::table('chuc_vus')
            ->where('slug_chuc_vu', 'quan-tri-phim')
            ->first();

        $phimActions = [
            'Thêm, Sữa Xoá Phim',
            'Thêm, Sữa Xoá Tập Phim',
            'Thêm, Sữa Xoá Thể Loại',
            'Thêm, Sữa Xoá Loại Phim',
            'Quản lý Leech Phim',
            'Quản lý SLide',
            'Thống Kê'
        ];

        foreach($phimActions as $actionName) {
            $action = DB::table('actions')
                ->where('ten_chuc_nang', $actionName)
                ->first();

            if ($action) {
                $phanQuyens[] = [
                    'id_chuc_vu' => $quanTriPhim->id,
                    'id_chuc_nang' => $action->id,

                ];
            }
        }

        // Quản trị Blog có quyền liên quan đến blog
        $quanTriBlog = DB::table('chuc_vus')
            ->where('slug_chuc_vu', 'quan-tri-blog')
            ->first();

        $blogActions = [
            'Thêm, Sữa Xoá BLOG',
            'Thêm, Sữa Xoá Chuyên Mục BLOG',
            'Thêm, Sữa Xoá Tác Giả',
            'Thống Kê'
        ];

        foreach($blogActions as $actionName) {
            $action = DB::table('actions')
                ->where('ten_chuc_nang', $actionName)
                ->first();

            if ($action) {
                $phanQuyens[] = [
                    'id_chuc_vu' => $quanTriBlog->id,
                    'id_chuc_nang' => $action->id,

                ];
            }
        }

        DB::table('phan_quyens')->insert($phanQuyens);
    }
}

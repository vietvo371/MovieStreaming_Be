<?php

use App\Http\Controllers\AdminAnimeController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\BinhLuanBaiVietController;
use App\Http\Controllers\BinhLuanPhimController;
use App\Http\Controllers\ChuyenMucController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\LoaiPhimController;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\TacGiaController;
use App\Http\Controllers\TheLoaiController;
use App\Http\Controllers\YeuThichController;
use App\Models\AdminAnime;
use App\Models\BinhLuanPhim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




///      ADMIN
    Route::post('/login',[AdminAnimeController::class , 'login']);
    Route::post('/register',[AdminAnimeController::class , 'register']);
    Route::post('/check',[AdminAnimeController::class , 'check']);
    Route::delete('/thong-tin-xoa/{id}', [AdminAnimeController::class, 'xoatoken']);
    /// Khach hàng
    Route::post('/khach-hang/login',[KhachHangController::class , 'login']);
    Route::post('/khach-hang/register',[KhachHangController::class , 'register']);
    Route::post('/khach-hang/check',[KhachHangController::class , 'check']);
    Route::delete('/khach-hang/thong-tin-xoa/{id}', [KhachHangController::class, 'xoatoken']);



Route::group(['prefix'  =>  '/admin', 'middleware' => 'adminAnime'], function() {
    Route::group(['prefix'  =>  '/admin' ], function() {
        //  Tài Khoản Admin
        Route::get('/lay-du-lieu', [AdminAnimeController::class, 'getData']);
        Route::post('/thong-tin-tao', [AdminAnimeController::class, 'taoAdmin']);
        Route::delete('/thong-tin-xoa/{id}', [AdminAnimeController::class, 'xoaAdmin']);
        Route::put('/thong-tin-cap-nhat', [AdminAnimeController::class, 'capnhatAdmin']);
        Route::put('/thong-tin-thay-doi-trang-thai', [AdminAnimeController::class, 'thaydoiTrangThaiAdmin']);
        Route::post('/thong-tin-tim', [AdminAnimeController::class, 'timAdmin']);


    });
    Route::group(['prefix'  =>  '/khach-hang'], function() {
        //  Tài Khoản Khách Hàng
        Route::get('/lay-du-lieu', [KhachHangController::class, 'getData']);
        Route::post('/thong-tin-tao', [KhachHangController::class, 'taoKhachHang']);
        Route::delete('/thong-tin-xoa/{id}', [KhachHangController::class, 'xoaKhachHang']);
        Route::put('/thong-tin-cap-nhat', [KhachHangController::class, 'capnhatKhachHang']);
        Route::put('/thong-tin-thay-doi-trang-thai', [KhachHangController::class, 'thaydoiTrangThaiKhachHang']);
        Route::post('/thong-tin-tim', [KhachHangController::class, 'timKhachHang']);



    });
    Route::group(['prefix'  =>  '/phim' ], function() {
        //  Phim
        Route::get('/lay-du-lieu', [PhimController::class, 'getData']);
        Route::post('/thong-tin-tao', [PhimController::class, 'taoPhim']);
        Route::delete('/thong-tin-xoa/{id}', [PhimController::class, 'xoaPhim']);
        Route::put('/thong-tin-cap-nhat', [PhimController::class, 'capnhatPhim']);
        Route::put('/thong-tin-thay-doi-trang-thai', [PhimController::class, 'thaydoiTrangThaiPhim']);
        Route::post('/thong-tin-tim', [PhimController::class, 'timPhim']);


    });
    Route::group(['prefix'  =>  '/the-loai'], function() {

        // The Loai Phim
        Route::get('/lay-du-lieu', [TheLoaiController::class, 'getData']);
        Route::post('/thong-tin-tao', [TheLoaiController::class, 'taoTheLoai']);
        Route::delete('/thong-tin-xoa/{id}', [TheLoaiController::class, 'xoaTheLoai']);
        Route::put('/thong-tin-cap-nhat', [TheLoaiController::class, 'capnhatTheLoai']);
        Route::put('/thong-tin-thay-doi-trang-thai', [TheLoaiController::class, 'thaydoiTrangThaiTheLoai']);
        Route::post('/thong-tin-tim', [TheLoaiController::class, 'timTheLoai']);


    });
    Route::group(['prefix'  =>  '/loai-phim'], function() {

        // Loại Phim
        Route::get('/lay-du-lieu', [LoaiPhimController::class, 'getData']);
        Route::post('/thong-tin-tao', [LoaiPhimController::class, 'taoLoaiPhim']);
        Route::delete('/thong-tin-xoa/{id}', [LoaiPhimController::class, 'xoaLoaiPhim']);
        Route::put('/thong-tin-cap-nhat', [LoaiPhimController::class, 'capnhatLoaiPhim']);
        Route::put('/thong-tin-thay-doi-trang-thai', [LoaiPhimController::class, 'thaydoiTrangThaiLoaiPhim']);
        Route::post('/thong-tin-tim', [LoaiPhimController::class, 'timLoaiPhim']);

    });
    Route::group(['prefix'  =>  '/tac-gia'], function() {

        // Tác Giả
        Route::get('/lay-du-lieu', [TacGiaController::class, 'getData']);
        Route::post('/thong-tin-tao', [TacGiaController::class, 'taoBaiViet']);
        Route::delete('/thong-tin-xoa/{id}', [TacGiaController::class, 'xoaTacGia']);
        Route::put('/thong-tin-cap-nhat', [TacGiaController::class, 'capnhatTacGia']);
        Route::put('/thong-tin-thay-doi-trang-thai', [TacGiaController::class, 'thaydoiTrangThaiTacGia']);
        Route::post('/thong-tin-tim', [TacGiaController::class, 'timTacGia']);

    });
    Route::group(['prefix'  =>  '/bai-viet'], function() {
        // Bài Viết Blog
        Route::get('/lay-du-lieu', [BaiVietController::class, 'getData']);
        Route::post('/thong-tin-tao', [BaiVietController::class, 'taoBaiViet']);
        Route::delete('/thong-tin-xoa/{id}', [BaiVietController::class, 'xoaBaiViet']);
        Route::put('/thong-tin-cap-nhat', [BaiVietController::class, 'capnhatBaiViet']);
        Route::put('/thong-tin-thay-doi-trang-thai', [BaiVietController::class, 'thaydoiTrangThaiBaiViet']);
        Route::post('/thong-tin-tim', [BaiVietController::class, 'timBaiViet']);
    });
    Route::group(['prefix'  =>  '/chuyen-muc'], function() {
        // Chuyên Mục Blog
        Route::get('/lay-du-lieu', [ChuyenMucController::class, 'getData']);
        Route::post('/thong-tin-tao', [ChuyenMucController::class, 'taoChuyenMuc']);
        Route::delete('/thong-tin-xoa/{id}', [ChuyenMucController::class, 'xoaChuyenMuc']);
        Route::put('/thong-tin-cap-nhat', [ChuyenMucController::class, 'capnhatChuyenMuc']);
        Route::put('/thong-tin-thay-doi-trang-thai', [ChuyenMucController::class, 'thaydoiTrangThaiChuyenMuc']);
        Route::post('/thong-tin-tim', [ChuyenMucController::class, 'timChuyenMuc']);
    });
    Route::group(['prefix'  =>  '/yeu-thich'], function() {
        // Yêu Thich
        Route::get('/lay-du-lieu', [YeuThichController::class, 'getData']);
        Route::post('/thong-tin-tao', [YeuThichController::class, 'taoYeuThich']);
        Route::post('/kiem-tra', [YeuThichController::class, 'checkYeuThich']);
        Route::post('/thong-tin-xoa', [YeuThichController::class, 'xoaYeuThich']);
        Route::put('/thong-tin-cap-nhat', [YeuThichController::class, 'capnhatYeuThich']);
        Route::put('/thong-tin-thay-doi-trang-thai', [YeuThichController::class, 'thaydoiTrangThaiYeuThich']);
        Route::post('/thong-tin-tim', [YeuThichController::class, 'timYeuThich']);
    });
    Route::group(['prefix'  =>  '/binh-luan-phim'], function() {
        // Bình luận Phim
        Route::get('/lay-du-lieu', [BinhLuanPhimController::class, 'getData']);
        Route::post('/thong-tin-tao', [BinhLuanPhimController::class, 'taoBinhLuanPhim']);
        Route::delete('/thong-tin-xoa/{id}', [BinhLuanPhimController::class, 'xoaBinhLuanPhim']);
    });
    Route::group(['prefix'  =>  '/binh-luan-blog'], function() {
        // Bình luận Blog
        Route::get('/lay-du-lieu', [BinhLuanBaiVietController::class, 'getData']);
        Route::post('/thong-tin-tao', [BinhLuanBaiVietController::class, 'taoBinhLuanBlog']);
        Route::delete('/thong-tin-xoa/{id}', [BinhLuanBaiVietController::class, 'xoaBinhLuanBlog']);
    });

});

 // Show data ở Home
    Route::group(['prefix'  =>  '/phim' ], function() {
        //  Phim
        Route::get('/lay-du-lieu-show', [PhimController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/the-loai'], function() {
        // The Loai Phim
        Route::get('/lay-du-lieu-show', [TheLoaiController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/loai-phim'], function() {
        // Loại Phim
        Route::get('/lay-du-lieu-show', [LoaiPhimController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/tac-gia'], function() {
        // Tác Giả
        Route::get('/lay-du-lieu-show', [TacGiaController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/bai-viet'], function() {
        // Bài Viết Blog
        Route::get('/lay-du-lieu-show', [BaiVietController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/chuyen-muc'], function() {
        // Chuyên Mục Blog
        Route::get('/lay-du-lieu-show', [ChuyenMucController::class, 'getDataHome']);
    });
    Route::group(['prefix'  =>  '/binh-luan-phim'], function() {
        // Bình luận Phim
        Route::get('/lay-du-lieu-show', [BinhLuanPhimController::class, 'getData']);
    });
    Route::group(['prefix'  =>  '/binh-luan-blog'], function() {
        // Bình luận Blog
        Route::get('/lay-du-lieu-show', [BinhLuanBaiVietController::class, 'getData']);
    });

    Route::post('/phim/thong-tin-tim', [PhimController::class, 'timPhimHome']);
    Route::get('/the-loai/sap-xep', [TheLoaiController::class, 'sapxepHome']);
    Route::get('/loai-phim/sap-xep', [LoaiPhimController::class, 'sapxepHome']);





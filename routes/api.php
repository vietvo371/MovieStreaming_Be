<?php

use App\Http\Controllers\AdminAnimeController;
use App\Http\Controllers\LoaiPhimController;
use App\Http\Controllers\PhimController;
use App\Http\Controllers\TheLoaiController;
use App\Models\AdminAnime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




///      ADMIN
    Route::post('/login',[AdminAnimeController::class , 'login']);
    Route::post('/register',[AdminAnimeController::class , 'register']);
    Route::post('/check',[AdminAnimeController::class , 'check']);
    Route::delete('/thong-tin-xoa/{id}', [AdminAnimeController::class, 'xoatoken']);



Route::group(['prefix'  =>  '/admin','middleware' => 'adminAnime'], function() {
    Route::group(['prefix'  =>  '/phim'], function() {
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

});

<?php

namespace App\Http\Controllers;

use App\Models\TapPhim;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class TapPhimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData()
    {
        $dataAmin       = TapPhim::join('phims','id_phim','phims.id')
                                    ->select('tap_phims.*','phims.ten_phim')
                         ->get(); // get là ra 1 danh sách
           return response()->json([
           'tap_phim_admin'  =>  $dataAmin,
           ]);
    }

    public function taoTapPhim(Request $request)
    {
        try {
            TapPhim::create([
                        'ten_tap_phim'  => $request-> ten_tap_phim,
                        'slug_tap_phim' => $request-> slug_tap_phim ,
                        'url'           => $request-> url,
                        'id_phim'       => $request-> id_phim,
                        'tinh_trang'    => $request-> tinh_trang,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => ' thêm Tập Phim thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá Tập Phim không thành công!!'
                ]);
        }
    }
    public function timTapPhim(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = TapPhim::select('the_loais.*')
                    ->where('ten_the_loai', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'the_loai'  =>  $data,
        ]);
    }
    public function capnhatTapPhim(Request $request)
    {
        try {
            TapPhim::where('id', $request->id)
                    ->update([
                        'ten_tap_phim'  => $request-> ten_tap_phim,
                        'slug_tap_phim' => $request-> slug_tap_phim ,
                        'url'           => $request-> url,
                        'id_phim'       => $request-> id_phim,
                        'tinh_trang'    => $request-> tinh_trang,
                            ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật thành ' . $request->ten_the_loai,
            ]);
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Thể Loại không thành công!!'
            ]);
        }
    }
    public function xoaTapPhim($id)
    {
        try {
            TapPhim::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Thể Loại thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Thể Loại không thành công!!'
            ]);

        }

    }
    public function thaydoiTrangThaiTapPhim(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            TapPhim::where('id', $request->id)
                    ->update([
                        'tinh_trang'    =>$tinh_trang_moi
                    ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Cập Nhật Trạng Thái thành công!! '
            ]);
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Trạng Thái không thành công!!'
            ]);
        }
    }
    public function kiemTraSlugTapPhim(Request $request)
    {
        $tac_gia = TapPhim::where('slug_tap_phim', $request->slug)->first();

        if(!$tac_gia) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Tập Phim phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Tập Phim Đã Tồn Tại!',
            ]);
        }
    }
    public function kiemTraSlugTapPhimUpdate(Request $request)
    {
        $mon_an = TapPhim::where('slug_tap_phim', $request->slug)
                                     ->where('id', '<>' , $request->id)
                                     ->first();

        if(!$mon_an) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Tập Phim phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Tập Phim Đã Tồn Tại!',
            ]);
        }
    }
}

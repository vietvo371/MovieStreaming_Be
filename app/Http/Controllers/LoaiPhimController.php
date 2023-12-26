<?php

namespace App\Http\Controllers;

use App\Models\LoaiPhim;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class LoaiPhimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData()
    {
        $data   = LoaiPhim::select('loai_phims.*')
         ->get(); // get là ra 1 danh sách
            return response()->json([
            'loai_phim'  =>  $data,
            ]);
     }

     public function taoLoaiPhim(Request $request)
     {
         try {
             LoaiPhim::create([
                'ten_loai_phim'          =>$request->ten_loai_phim,
                'slug_loai_phim'         =>$request->slug_loai_phim,
                'tinh_trang'             =>$request->tinh_trang,
                 ]);
                 return response()->json([
                     'status'   => true ,
                     'message'  => 'Bạn thêm Loại Phim thành công!',
                 ]);
         } catch (ExceptionEvent $e) {
                 return response()->json([
                     'status'     => false,
                     'message'    => 'Xoá Loại Phim không thành công!!'
                 ]);
         }
     }
     public function timLoaiPhim(Request $request)
     {
         $key    = '%'. $request->key . '%';
         $data   = LoaiPhim::select('loai_phims.*')
                     ->where('ten_loai_phim', 'like', $key)
                     ->get(); // get là ra 1 danh sách
         return response()->json([
         'loai_phim'  =>  $data,
         ]);
     }
     public function capnhatLoaiPhim(Request $request)
     {
         try {
             LoaiPhim::where('id', $request->id)
                     ->update([
                        'ten_loai_phim'          =>$request->ten_loai_phim,
                        'slug_loai_phim'         =>$request->slug_loai_phim,
                        'tinh_trang'             =>$request->tinh_trang,
                             ]);
             return response()->json([
                 'status'     => true,
                 'message'    => 'Đã Cập Nhật thành ' . $request->ten_loai_phim,
             ]);
         } catch (Exception $e) {
             //throw $th;
             return response()->json([
                 'status'     => false,
                 'message'    => 'Cập Nhật  Loại Phim không thành công!!'
             ]);
         }
     }
     public function xoaLoaiPhim($id)
     {
         try {
             LoaiPhim::where('id', $id)->delete();

             return response()->json([
                 'status'     => true,
                 'message'    => 'Đã xoá Loai Phim thành công!!'
             ]);
         } catch (ExceptionEvent $e) {
             //throw $th;
             return response()->json([
                 'status'     => false,
                 'message'    => 'Xoá  Loai Phim không thành công!!'
             ]);

         }

     }
     public function thaydoiTrangThaiLoaiPhim(Request $request)
     {

         try {
             $tinh_trang_moi = !$request->tinh_trang;
             //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
             LoaiPhim::where('id', $request->id)
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
}

<?php

namespace App\Http\Controllers;

use App\Models\TheLoai;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class TheLoaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData()
    {
        $data   = TheLoai::select('the_loais.*')
        ->get(); // get là ra 1 danh sách
           return response()->json([
           'the_loai'  =>  $data,
           ]);
    }


    public function taoTheLoai(Request $request)
    {
        try {
            TheLoai::create([
                'ten_the_loai'      =>$request->ten_the_loai,
                'slug_the_loai'     =>$request->slug_the_loai,
                'tinh_trang'        =>$request->tinh_trang,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => 'Bạn thêm Thể Loại Phim thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá Thể Loại Phim không thành công!!'
                ]);
        }
    }
    public function timTheLoai(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = TheLoai::select('the_loais.*')
                    ->where('ten_the_loai', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'the_loai'  =>  $data,
        ]);
    }
    public function capnhatTheLoai(Request $request)
    {
        try {
            TheLoai::where('id', $request->id)
                    ->update([
                        'ten_the_loai'      =>$request->ten_the_loai,
                        'slug_the_loai'     =>$request->slug_the_loai,
                        'tinh_trang'        =>$request->tinh_trang,
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
    public function xoaTheLoai($id)
    {
        try {
            TheLoai::where('id', $id)->delete();

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
    public function thaydoiTrangThaiTheLoai(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            TheLoai::where('id', $request->id)
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

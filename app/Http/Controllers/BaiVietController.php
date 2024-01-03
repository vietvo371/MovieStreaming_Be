<?php

namespace App\Http\Controllers;

use App\Models\BaiViet;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class BaiVietController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData()
    {
        $dataAdmim   = BaiViet::join('chuyen_mucs','id_chuyen_muc','chuyen_mucs.id')
                        ->select('bai_viets.*','chuyen_mucs.ten_chuyen_muc')
                        ->get(); // get là ra 1 danh sách
           return response()->json([
           'bai_viet_admin'  =>  $dataAdmim,
           ]);
    }
    public function getDataHome()
    {
        $data   = BaiViet::where('bai_viets.tinh_trang',1)
                        ->join('chuyen_mucs','id_chuyen_muc','chuyen_mucs.id')
                        ->select('bai_viets.*','chuyen_mucs.ten_chuyen_muc')
                        ->get(); // get là ra 1 danh sách

           return response()->json([
           'bai_viet'        =>  $data,
           ]);
    }


    public function taoBaiViet(Request $request)
    {
        try {
            BaiViet::create([
                'tieu_de'               =>$request->tieu_de,
                'hinh_anh'              =>$request->hinh_anh,
                'mo_ta'                 =>$request->mo_ta,
                'mo_ta_chi_tiet'        =>$request->mo_ta_chi_tiet,
                'id_chuyen_muc'         =>$request->id_chuyen_muc,
                'tinh_trang'            =>$request->tinh_trang,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => 'Bạn thêm bài viết thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá bài viết không thành công!!'
                ]);
        }

    }

     public function timBaiViet(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = BaiViet::select('bai_viets.*')
                    ->where('tieu_de', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'bai_viet'  =>  $data,
        ]);
    }
    public function xoaBaiViet($id)
    {
        try {
            BaiViet::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá bài viết thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá bài viết không thành công!!'
            ]);

        }

    }

    public function capnhatBaiViet(Request $request)
    {
        try {
            BaiViet::where('id', $request->id)
                    ->update([
                        'tieu_de'               =>$request->tieu_de,
                        'hinh_anh'              =>$request->hinh_anh,
                        'mo_ta'                 =>$request->mo_ta,
                        'mo_ta_chi_tiet'        =>$request->mo_ta_chi_tiet,
                        'id_chuyen_muc'         =>$request->id_chuyen_muc,
                        'tinh_trang'            =>$request->tinh_trang,
                    ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật bài viết thành công!' ,
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật bài viết không thành công!!'
            ]);
        }
    }

    public function thaydoiTrangThaiBaiViet(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            BaiViet::where('id', $request->id)
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

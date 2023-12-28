<?php

namespace App\Http\Controllers;

use App\Models\Phim;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class PhimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData()
    {
        $data   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        // ->take(3)
                        ->get(); // get là ra 1 danh sách
        $dataHD   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('id_the_loai', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->take(6)
                        ->get(); // get là ra 1 danh sách
        return response()->json([
        'phim'  =>  $data,
        'phimHD' => $dataHD,
        ]);
    }


    public function taoPhim(Request $request)
    {
        try {
            Phim::create([
                'ten_phim'                  =>$request->ten_phim,
                'hinh_anh'                  =>$request->hinh_anh,
                'mo_ta'                     =>$request->mo_ta,
                'url'                       =>$request->url,
                'id_loai_phim'              =>$request->id_loai_phim,
                'id_the_loai'               =>$request->id_the_loai,
                'id_tac_gia'                =>$request->id_tac_gia,
                'tinh_trang'                =>$request->tinh_trang,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => 'Bạn thêm Phim thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá Phim không thành công!!'
                ]);
        }

    }

     public function timPhim(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = Phim::join('the_loais','id_the_loai','the_loais.id')
                    ->join('loai_phims','id_loai_phim','loai_phims.id')
                    ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim')
                    ->where('ten_phim', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'phim'  =>  $data,
        ]);
    }
    public function xoaPhim($id)
    {
        try {
            Phim::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Phim thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Phim không thành công!!'
            ]);

        }

    }

    public function capnhatPhim(Request $request)
    {
        try {
            Phim::where('id', $request->id)
                    ->update([
                        'ten_phim'                  =>$request->ten_phim,
                        'hinh_anh'                  =>$request->hinh_anh,
                        'mo_ta'                     =>$request->mo_ta,
                        'url'                       =>$request->url,
                        'id_loai_phim'              =>$request->id_loai_phim,
                        'id_the_loai'               =>$request->id_the_loai,
                        'id_tac_gia'                =>$request->id_tac_gia,
                        'tinh_trang'                =>$request->tinh_trang,
                    ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật thành ' . $request->ten_phim,
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Phim không thành công!!'
            ]);
        }
    }

    public function thaydoiTrangThaiPhim(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            Phim::where('id', $request->id)
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

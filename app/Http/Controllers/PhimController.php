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

        $dataAdmin   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->get(); // get là ra 1 danh sách

        return response()->json([
        'phim_admin' => $dataAdmin,
        ]);
    }
    public function getDataHome()
    {
        $data   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('phims.tinh_trang', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->get(); // get là ra 1 danh sách
        $data9   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('phims.tinh_trang', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->inRandomOrder() // Lấy ngẫu nhiên
                        ->take(9)
                        ->get(); // get là ra 1 danh sách
        $data2   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('phims.tinh_trang', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->take(2)
                        ->get(); // get là ra 1 danh sách
        $data3   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('phims.tinh_trang', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->orderBy('id', 'DESC') // sắp xếp giảm dần
                        ->take(3)
                        ->get(); // get là ra 1 danh sách
        $data5   = Phim::join('the_loais','id_the_loai','the_loais.id')
                        ->join('loai_phims','id_loai_phim','loai_phims.id')
                        ->join('tac_gias','id_tac_gia','tac_gias.id')
                        ->where('phims.tinh_trang', 1)
                        ->select('phims.*','the_loais.ten_the_loai','loai_phims.ten_loai_phim','tac_gias.ten_tac_gia')
                        ->inRandomOrder() // Lấy ngẫu nhiên
                        ->take(5)
                        ->get(); // get là ra 1 danh sách
        return response()->json([
        'phim'             =>  $data,
        'phim_9_obj'       =>  $data9,
        'phim_2_obj'       =>  $data2,
        'phim_3_obj'       =>  $data3,
        'phim_5_obj'       =>  $data5,
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
    public function timPhimHome(Request $request)
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
}

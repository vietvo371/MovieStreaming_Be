<?php

namespace App\Http\Controllers;

use App\Models\PhanQuyen;
use App\Models\TacGia;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class TacGiaController extends Controller
{

    public function getData()
    {
        $id_chuc_nang = 9;
        $user   = Auth::guard('sanctum')->user(); // Chính là người đang login
        $user_chuc_vu   = $user->id_chuc_vu;    // Giả sử
        $check  = PhanQuyen::where('id_chuc_vu', $user_chuc_vu)
            ->where('id_chuc_nang', $id_chuc_nang)
            ->first();
        if (!$check) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $dataAdmin   = TacGia::select('tac_gias.*')
            ->get(); // get là ra 1 danh sách
        return response()->json([
            'tac_gia_admin'  =>  $dataAdmin,
        ]);
    }
    public function getDataHome()
    {
        $data   = TacGia::where('tac_gias.tinh_trang', 1)
            ->select('tac_gias.*')
            ->get(); // get là ra 1 danh sách
        return response()->json([
            'tac_gia'  =>  $data,
        ]);
    }

    public function taoTacGia(Request $request)
    {
        try {
            $id_chuc_nang = 9;
            $user   = Auth::guard('sanctum')->user(); // Chính là người đang login
            $user_chuc_vu   = $user->id_chuc_vu;    // Giả sử
            $check  = PhanQuyen::where('id_chuc_vu', $user_chuc_vu)
                ->where('id_chuc_nang', $id_chuc_nang)
                ->first();
            if (!$check) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            TacGia::create([
                'ten_tac_gia'            => $request->ten_tac_gia,
                'slug_tac_gia'            => $request->slug_tac_gia,
                'tinh_trang'             => $request->tinh_trang,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn thêm Tác Giả thành công!',
            ]);
        } catch (ExceptionEvent $e) {
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Tác Giả không thành công!!'
            ]);
        }
    }
    public function timTacGia(Request $request)
    {
        $key    = '%' . $request->key . '%';
        $data   = TacGia::select('tac_gias.*')
            ->where('ten_tac_gia', 'like', $key)
            ->get(); // get là ra 1 danh sách
        return response()->json([
            'tac_gia'  =>  $data,
        ]);
    }
    public function capnhatTacGia(Request $request)
    {
        try {
            $id_chuc_nang = 9;
            $user   = Auth::guard('sanctum')->user(); // Chính là người đang login
            $user_chuc_vu   = $user->id_chuc_vu;    // Giả sử
            $check  = PhanQuyen::where('id_chuc_vu', $user_chuc_vu)
                ->where('id_chuc_nang', $id_chuc_nang)
                ->first();
            if (!$check) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            TacGia::where('id', $request->id)
                ->update([
                    'ten_tac_gia'            => $request->ten_tac_gia,
                    'slug_tac_gia'            => $request->slug_tac_gia,
                    'tinh_trang'             => $request->tinh_trang,
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
    public function xoaTacGia($id)
    {
        try {
            $id_chuc_nang = 9;
            $user   = Auth::guard('sanctum')->user(); // Chính là người đang login
            $user_chuc_vu   = $user->id_chuc_vu;    // Giả sử
            $check  = PhanQuyen::where('id_chuc_vu', $user_chuc_vu)
                ->where('id_chuc_nang', $id_chuc_nang)
                ->first();
            if (!$check) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            TacGia::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Tác Giả thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá  Tác Giả không thành công!!'
            ]);
        }
    }
    public function thaydoiTrangThaiTacGia(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            TacGia::where('id', $request->id)
                ->update([
                    'tinh_trang'    => $tinh_trang_moi
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
    public function kiemTraSlugTacGia(Request $request)
    {
        $tac_gia = TacGia::where('slug_tac_gia', $request->slug)->first();

        if (!$tac_gia) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Tác Giả phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Tác Giả Đã Tồn Tại!',
            ]);
        }
    }
    public function kiemTraSlugTacGiaUpdate(Request $request)
    {
        $mon_an = TacGia::where('slug_tac_gia', $request->slug)
            ->where('id', '<>', $request->id)
            ->first();

        if (!$mon_an) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Tác Giả phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Tác Giả Đã Tồn Tại!',
            ]);
        }
    }
}

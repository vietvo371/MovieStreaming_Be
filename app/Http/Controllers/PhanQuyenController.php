<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePhanQuyenRequest;
use App\Models\Action;
use App\Models\ChucVu;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhanQuyenController extends Controller
{
    public function getDataPhanQuyen()
    {
        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $listChucVu       = ChucVu::select('chuc_vus.*')
            ->get(); // get là ra 1 danh sách
        $listChucNang       = Action::select('actions.*')
            ->get(); // get là ra 1 danh sách
        return response()->json([
            'listChucVu'  =>  $listChucVu,
            'listChucNang'  =>  $listChucNang,
        ]);
    }
    public function createPhanQuyen(CreatePhanQuyenRequest $request)
    {
        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        // Check if the `PhanQuyen` already exists
        if (PhanQuyen::where('id_chuc_nang', $request->id_chuc_nang)
            ->where('id_chuc_vu', $request->id_chuc_vu)
            ->exists()
        ) {
            return response()->json([
                'status'  => false,
                'message' => 'Chức vụ đã có chức năng này!',
            ]);
        }

        // Create the new `PhanQuyen`
        PhanQuyen::create([
            'id_chuc_nang' => $request->id_chuc_nang,
            'id_chuc_vu'   => $request->id_chuc_vu,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã phân quyền thành công',
        ]);
    }

    public function getChucNang(Request $request)
    {
        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data   = PhanQuyen::join('actions', 'id_chuc_nang', 'actions.id')
            ->where('id_chuc_vu', $request->id)
            ->select('phan_quyens.*', 'actions.ten_chuc_nang')
            ->get();
        // $data   = PhanQuyen::where('id_chuc_vu', $request->id)->get();

        return response()->json([
            'data'   =>  $data
        ]);
    }

    public function xoaPhanQuyen($id)
    {
        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $phan_quyen = PhanQuyen::where('id', $id)->first();

        if ($phan_quyen) {
            $phan_quyen->delete();

            return response()->json([
                'status'    =>  true,
                'message'   =>  'Đã xóa phân quyền thành công'
            ]);
        } else {
            return response()->json([
                'status'    =>  false,
                'message'   =>  'Đã có lỗi xảy ra!'
            ]);
        }
    }
}

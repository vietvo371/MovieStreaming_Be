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
    public function createPhanQuyen(Request $request)
    {
        $id_chuc_nang = 4;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $idChucVu = $request->id_chuc_vu;
        $danhSachQuyenMoi = $request->danh_sach_quyen;

        // Lấy danh sách quyền cũ
        $quyenCu = PhanQuyen::where('id_chuc_vu', $idChucVu)->pluck('id_chuc_nang')->toArray();

        // Xác định quyền cần xóa (có trong cũ nhưng không có trong mới)
        $quyenCanXoa = array_diff($quyenCu, $danhSachQuyenMoi);
        if (!empty($quyenCanXoa)) {
            PhanQuyen::where('id_chuc_vu', $idChucVu)
                ->whereIn('id_chuc_nang', $quyenCanXoa)
                ->delete();
        }

        // Xác định quyền cần thêm (có trong mới nhưng không có trong cũ)
        $quyenCanThem = array_diff($danhSachQuyenMoi, $quyenCu);
        $data = [];
        foreach ($quyenCanThem as $idChucNang) {
            $data[] = PhanQuyen::create([
                'id_chuc_vu'  => $idChucVu,
                'id_chuc_nang'  => $idChucNang,
            ]);
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật phân quyền thành công!',
            'data'      => $data
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

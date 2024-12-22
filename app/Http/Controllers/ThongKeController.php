<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\PhanQuyen;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function getDataThongke1(Request $request) // Thống kê so tap theo loại phim
    {
        $id_chuc_nang = 12;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = Phim::join('loai_phims', 'phims.id_loai_phim', '=', 'loai_phims.id')
            ->where('phims.tinh_trang', 1)
            // ->whereDate('phims.created_at', ">=", $request->begin)
            // ->whereDate('phims.created_at', "<=", $request->end)
            ->select(
                DB::raw("COUNT(phims.id) as total"),
                'loai_phims.ten_loai_phim'
            )
            ->groupBy('loai_phims.ten_loai_phim')
            ->get();

        $list_label = [];
        $list_data = [];

        foreach ($data as $value) {
            $list_data[] = $value->total;
            $list_label[] = $value->ten_loai_phim;
        }
        return response()->json([
            'list_label' => $list_label,
            'list_data'  => $list_data,
        ]);
    }
    public function getDataThongkeDoanhThu(Request $request)
    {
        $id_chuc_nang = 13;
        $check = $this->checkQuyen($id_chuc_nang);
        // if (!$check) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Bạn không có quyền thực hiện chức năng này'
        //     ]);
        // }

        // // Add date validation
        // if (!$request->begin || !$request->end) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Ngày bắt đầu và ngày kết thúc không được để trống'
        //     ]);
        // }

        try {
            // Debug query
            $query = HoaDon::join('goi_vips', 'hoa_dons.id_goi', '=', 'goi_vips.id')
                ->where('hoa_dons.tinh_trang', 1)
                // ->whereDate('hoa_dons.created_at', '>=', $request->begin)
                // ->whereDate('hoa_dons.created_at', '<=', $request->end)
                ->select(
                    DB::raw("SUM(hoa_dons.tong_tien) as total_revenue"),
                    'goi_vips.ten_goi'
                )
                ->groupBy('goi_vips.ten_goi');

            // Log the SQL query

            $data = $query->get();

            // Check if data is empty
            if ($data->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Không có dữ liệu trong khoảng thời gian này',
                    'date_range' => [
                        'begin' => $request->begin,
                        'end' => $request->end
                    ]
                ]);
            }

            $list_label = [];
            $list_data = [];

            foreach ($data as $value) {
                $list_data[] = $value->total_revenue;
                $list_label[] = $value->ten_goi;
            }

            return response()->json([
                'status' => true,
                'list_label' => $list_label,
                'list_data'  => $list_data,
                'date_range' => [
                    'begin' => $request->begin,
                    'end' => $request->end
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
            ]);
        }
    }
}

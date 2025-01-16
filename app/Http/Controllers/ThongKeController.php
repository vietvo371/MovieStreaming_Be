<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\PhanQuyen;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function getDataThongKeLuotXem(Request $request)
    {
        $id_chuc_nang = 14;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $kieuThongKe = $request->kieu_thong_ke;
        $tuNgay = Carbon::parse($request->tu_ngay)->startOfDay();
        $denNgay = Carbon::parse($request->den_ngay)->endOfDay();

        $query = DB::table('phims')
            ->select(
                DB::raw('DATE(updated_at) as ngay'),
                DB::raw('SUM(tong_luot_xem) as total_views')
            )
            ->whereBetween('updated_at', [$tuNgay, $denNgay])
            ->groupBy('ngay');

        if ($kieuThongKe == 'thang') {
            $query = DB::table('phims')
                ->select(
                    DB::raw('DATE_FORMAT(updated_at, "%Y-%m") as thang'),
                    DB::raw('SUM(tong_luot_xem) as total_views')
                )
                ->whereBetween('updated_at', [$tuNgay, $denNgay])
                ->groupBy('thang');
        } else if ($kieuThongKe == 'nam') {
            $query = DB::table('phims')
                ->select(
                    DB::raw('YEAR(updated_at) as nam'),
                    DB::raw('SUM(tong_luot_xem) as total_views')
                )
                ->whereBetween('updated_at', [$tuNgay, $denNgay])
                ->groupBy('nam');
        }

        $data = $query->get();

        $labels = [];
        $viewData = [];

        foreach ($data as $item) {
            if ($kieuThongKe == 'ngay') {
                $labels[] = Carbon::parse($item->ngay)->format('d/m/Y');
                $viewData[] = $item->total_views;
            } else if ($kieuThongKe == 'thang') {
                $labels[] = Carbon::createFromFormat('Y-m', $item->thang)->format('m/Y');
                $viewData[] = $item->total_views;
            } else {
                $labels[] = $item->nam;
                $viewData[] = $item->total_views;
            }
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'data' => $viewData
        ]);
    }
    public function getThongKeChung()
    {
        $id_chuc_nang = 15; // Giả sử ID chức năng là 15
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        try {
            // Tổng số phim
            $tongPhim = DB::table('phims')
                ->where('tinh_trang', 1)
                ->count();

            // Tổng lượt xem
            $tongLuotXem = DB::table('phims')
                ->where('tinh_trang', 1)
                ->sum('tong_luot_xem');

            // Tổng người dùng
            $tongNguoiDung = DB::table('khach_hangs')
                ->where('is_block', 1)
                ->count();

            // Bình luận mới (trong 7 ngày gần nhất)
            $binhLuanMoi = DB::table('binh_luan_phims')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count();

            return response()->json([
                'status' => true,
                'tong_phim' => $tongPhim,
                'tong_luot_xem' => $tongLuotXem,
                'tong_nguoi_dung' => $tongNguoiDung,
                'binh_luan_moi' => $binhLuanMoi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
            ]);
        }
    }
    public function getThongKeBinhLuan(Request $request)
    {
        $id_chuc_nang = 16;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        try {
            $tuNgay = Carbon::parse($request->tu_ngay)->startOfDay();
            $denNgay = Carbon::parse($request->den_ngay)->endOfDay();

            // Top phim có nhiều bình luận nhất
            $topComments = DB::table('phims')
                ->join('binh_luan_phims', 'phims.id', '=', 'binh_luan_phims.id_phim')
                ->select(
                    'phims.ten_phim',
                    DB::raw('COUNT(binh_luan_phims.id) as so_binh_luan')
                )
                ->whereBetween('binh_luan_phims.created_at', [$tuNgay, $denNgay])
                ->where('phims.tinh_trang', 1)
                ->groupBy('phims.id', 'phims.ten_phim')
                ->orderBy('so_binh_luan', 'desc')
                ->limit(10)
                ->get();

            // Top phim có điểm đánh giá cao nhất
            $topRatings = DB::table('phims')
                ->join('binh_luan_phims', 'phims.id', '=', 'binh_luan_phims.id_phim')
                ->select(
                    'phims.ten_phim',
                    DB::raw('AVG(binh_luan_phims.so_sao) as diem_trung_binh')
                )
                ->whereBetween('binh_luan_phims.created_at', [$tuNgay, $denNgay])
                ->where('phims.tinh_trang', 1)
                ->where('binh_luan_phims.so_sao', '>', 0)
                ->groupBy('phims.id', 'phims.ten_phim')
                ->orderBy('diem_trung_binh', 'desc')
                ->limit(10)
                ->get();

            // Bình luận gần đây
            $recentComments = DB::table('binh_luan_phims')
                ->join('khach_hangs', 'binh_luan_phims.id_khach_hang', '=', 'khach_hangs.id')
                ->join('phims', 'binh_luan_phims.id_phim', '=', 'phims.id')
                ->select(
                    'binh_luan_phims.id',
                    'khach_hangs.ho_va_ten as ten_nguoi_dung',
                    'phims.ten_phim',
                    'binh_luan_phims.noi_dung',
                    'binh_luan_phims.created_at'
                )
                ->orderBy('binh_luan_phims.created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'status' => true,
                'top_comments' => $topComments,
                'top_ratings' => $topRatings->map(function($item) {
                    $item->diem_trung_binh = round($item->diem_trung_binh, 1);
                    return $item;
                }),
                'recent_comments' => $recentComments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
            ]);
        }
    }
    public function getThongKeYeuThich(Request $request)
    {
        $id_chuc_nang = 17;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        try {
            $tuNgay = Carbon::parse($request->tu_ngay)->startOfDay();
            $denNgay = Carbon::parse($request->den_ngay)->endOfDay();

            // Lấy danh sách phim và số lượt yêu thích
            $listYeuThich = DB::table('phims')
                ->leftJoin('yeu_thichs', 'phims.id', '=', 'yeu_thichs.id_phim')
                ->select(
                    'phims.ten_phim',
                    DB::raw('COUNT(yeu_thichs.id) as so_luot_thich')
                )
                ->where('phims.tinh_trang', 1)
                ->whereBetween('yeu_thichs.created_at', [$tuNgay, $denNgay])
                ->groupBy('phims.id', 'phims.ten_phim')
                ->orderBy('so_luot_thich', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'status' => true,
                'list_yeu_thich' => $listYeuThich
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
            ]);
        }
    }
}

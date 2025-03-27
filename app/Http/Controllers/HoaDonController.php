<?php

namespace App\Http\Controllers;

use App\Models\GoiVip;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HoaDonController extends Controller
{

    public function getDataHoaDon()
    {
        $id_chuc_nang = 17;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $dataAdmin = HoaDon::join('goi_vips', 'hoa_dons.id_goi', 'goi_vips.id')
            ->join('khach_hangs', 'hoa_dons.id_khach_hang', 'khach_hangs.id')
            ->select('hoa_dons.*', 'goi_vips.ten_goi', 'khach_hangs.ho_va_ten')
            ->orderBy('hoa_dons.created_at', 'DESC')
            ->paginate(12);
        $response = [
            'pagination' => [
                'total' => $dataAdmin->total(),
                'per_page' => $dataAdmin->perPage(),
                'current_page' => $dataAdmin->currentPage(),
                'last_page' => $dataAdmin->lastPage(),
                'from' => $dataAdmin->firstItem(),
                'to' => $dataAdmin->lastItem()
            ],
            'dataAdmin' => $dataAdmin
        ];
        return response()->json([
            'data' => $response
        ]);
    }
    public function thongTinTimKiem(Request $request)
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
            $query = HoaDon::query()
                ->join('goi_vips', 'hoa_dons.id_goi', 'goi_vips.id')
                ->join('khach_hangs', 'hoa_dons.id_khach_hang', 'khach_hangs.id')
                ->select(
                    'hoa_dons.*',
                    'goi_vips.ten_goi',
                    'khach_hangs.ho_va_ten'
                );

            // Tìm kiếm theo mã HD hoặc tên KH
            if ($request->search && $request->search != 'undefined') {
                $query->where(function($q) use ($request) {
                    $q->where('hoa_dons.ma_hoa_don', 'like', '%' . $request->search . '%')
                      ->orWhere('khach_hangs.ho_va_ten', 'like', '%' . $request->search . '%');
                });
            }

            // Lọc theo loại thanh toán
            if ($request->loai_thanh_toan && $request->loai_thanh_toan != 'undefined') {
                if ($request->loai_thanh_toan === 'mbbank') {
                    $query->whereNull('hoa_dons.loai_thanh_toan');
                } else {
                    $query->where('hoa_dons.loai_thanh_toan', $request->loai_thanh_toan);
                }
            }

            // Lọc theo trạng thái thanh toán
            if ($request->has('tinh_trang') && $request->tinh_trang != 'undefined') {
                $query->where('hoa_dons.tinh_trang', $request->tinh_trang);
            }

            // Lọc theo gói VIP
            if ($request->id_goi && $request->id_goi != 'undefined') {
                $query->where('hoa_dons.id_goi', $request->id_goi);
            }

            // Lọc theo thời gian
            if ($request->date_from && $request->date_from != 'undefined') {
                $query->whereBetween('hoa_dons.created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);
            }

            // Sắp xếp
            $query->orderBy('hoa_dons.created_at', 'DESC');

            // Phân trang
            $dataAdmin = $query->paginate(12);

            return response()->json([
                'status' => true,
                'data' => [
                    'pagination' => [
                        'total' => $dataAdmin->total(),
                        'per_page' => $dataAdmin->perPage(),
                        'current_page' => $dataAdmin->currentPage(),
                        'last_page' => $dataAdmin->lastPage(),
                        'from' => $dataAdmin->firstItem(),
                        'to' => $dataAdmin->lastItem()
                    ],
                    'dataAdmin' => $dataAdmin
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    public function chiTietHoaDon($id)
    {
        $id_chuc_nang = 17;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $data = HoaDon::join('goi_vips', 'hoa_dons.id_goi', 'goi_vips.id')
            ->join('khach_hangs', 'hoa_dons.id_khach_hang', 'khach_hangs.id')
            ->join('giao_diches', 'hoa_dons.ma_hoa_don', 'giao_diches.ma_giao_dich')
            ->select('hoa_dons.*', 'goi_vips.ten_goi', 'khach_hangs.ho_va_ten', 'giao_diches.orderInfo', 'giao_diches.transactionNo', 'giao_diches.paymentType', 'giao_diches.responseCode', 'giao_diches.transactionStatus')
            ->find($id);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy dữ liệu.'
            ]);
        }
        return response()->json([
            'data' => $data
        ]);
    }
    public function getTrensactionOpen(Request $request)
    {

        $user = $this->isUser();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }

        $data = HoaDon::leftJoin('goi_vips', 'hoa_dons.id_goi', 'goi_vips.id')
            ->where('hoa_dons.id_khach_hang', $user->id)
            ->where('hoa_dons.tinh_trang', 1)
            ->select('hoa_dons.*', 'goi_vips.ten_goi')
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Không có dữ liệu.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    public function getDataCheckOut(Request $request)
    {
        $goi = GoiVip::where('tinh_trang', 1)->find($request->id_goi);
        if (!$goi) {
            return response()->json([
                'status' => false,
                'message' => 'Gói VIP không tồn tại hoặc không khả dụng.'
            ]);
        }
        $user = $this->isUser();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }
        $check = false;
        $existingVip = HoaDon::where('id_khach_hang', $user->id)
            ->where('ngay_ket_thuc', '>=', Carbon::now('Asia/Ho_Chi_Minh'))
            ->where('tinh_trang', 1) // Đã thanh toán
            ->first();

        if ($existingVip) {
            $check = true;
        }

        return response()->json([
            'status' => true,
            'user'   => $user,
            'goi'    => $goi,
            'check'  => $check,
        ]);
    }
    public function getQrPayMent(Request $request)
    {
        $goi = GoiVip::where('tinh_trang', 1)->find($request->id_goi);
        if (!$goi) {
            return response()->json([
                'status' => false,
                'message' => 'Gói VIP không tồn tại hoặc không khả dụng.'
            ]);
        }

        $user = $this->isUser();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }

        // Kiểm tra xem user đã có gói VIP hoạt động chưa
        $existingVip = HoaDon::where('id_khach_hang', $user->id)
            ->where('ngay_ket_thuc', '>=', Carbon::now('Asia/Ho_Chi_Minh'))
            ->where('tinh_trang', 1) // Đã thanh toán
            ->first();

        if ($existingVip) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã có gói VIP đang hoạt động. Vui lòng chờ đến khi hết hạn để đăng ký gói mới.'
            ]);
        }

        $hoaDon = $this->createHoaDon($goi, $user);
        $link = $this->generatePaymentLink($hoaDon);

        return response()->json([
            'status' => true,
            'link' => $link,
            'hoaDon' => $hoaDon,
            'user' => $user,
        ]);
    }

    private function createHoaDon($goi, $user)
    {
        $hoaDon = HoaDon::create([
            'id_goi'        => $goi->id,
            'id_khach_hang' => $user->id,
            'tong_tien'     => $goi->tien_sale > 0 ? $goi->tien_sale : $goi->tien_goc,
            'ngay_bat_dau'  => Carbon::now('Asia/Ho_Chi_Minh'),
            'ngay_ket_thuc' => Carbon::now('Asia/Ho_Chi_Minh')->addMonths($goi->thoi_han),
            'tinh_trang'    => 0, // Mặc định là chưa thanh toán
        ]);

        $hoaDon->ma_hoa_don = 'HD0' . substr(md5($hoaDon->id . time()), 0, 5);
        $hoaDon->save();

        return $hoaDon;
    }

    private function generatePaymentLink($hoaDon)
    {
        return 'https://img.vietqr.io/image/mb-0708585120-compact2.jpg?amount='
            . $hoaDon->tong_tien
            . '&addInfo=' . $hoaDon->ma_hoa_don
            . '&accountName=VO_VAN_VIET';
    }
}

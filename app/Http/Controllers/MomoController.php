<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MomoTransaction;
use App\Models\GoiVip;
use App\Models\HoaDon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MomoController extends Controller
{

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
    public function createPayment(Request $request)
    {
        try {
            // Validate request
            $user = $this->isUser();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn chưa đăng nhập'
                ], 401);
            }
            $request->validate([
                'id_goi' => 'required|exists:goi_vips,id'
            ]);

            $goiVip = GoiVip::find($request->id_goi);
            if (!$goiVip) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy gói VIP'
                ], 404);
            }
            $hoaDon = $this->createHoaDon($goiVip, $user);
            $partnerCode = env('MOMO_PARTNER_CODE');
            $accessKey = env('MOMO_ACCESS_KEY');
            $secretKey = env('MOMO_SECRET_KEY');
            $redirectUrl = env('MOMO_REDIRECT_URL') . "?type=momo";
            $ipnUrl = env('MOMO_IPN_URL') . $user->email;

            $orderInfo = "Thanh toán gói VIP: " . $goiVip->ten_goi;
            $amount = $goiVip->tien_sale;
            $orderId = $hoaDon->ma_hoa_don;
            $requestId = time();
            $requestType = "captureWallet";
            $extraData = base64_encode(json_encode([
                'package_id' => $goiVip->id,
                'user_id' => auth()->id()
            ]));

            // Tạo chữ ký SHA256
            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            // Gửi request đến MoMo
            $response = Http::post("https://test-payment.momo.vn/v2/gateway/api/create", $data);
            $result = $response->json();

            if (!isset($result['payUrl'])) {
                throw new \Exception('Không nhận được payUrl từ MoMo');
            }

            // Lưu transaction
            // MomoTransaction::create([
            //     'order_id' => $orderId,
            //     'user_id' => auth()->id(),
            //     'package_id' => $goiVip->id,
            //     'amount' => $amount,
            //     'status' => 'pending',
            //     'request_data' => json_encode($data)
            // ]);

            return response()->json([
                'status' => true,
                'payUrl' => $result['payUrl']
            ]);
        } catch (\Exception $e) {
            Log::error('Momo payment error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function momoPost(Request $request)
    {
        if ($request->has('resultCode') && $request->input('resultCode') == 0) {
            $customer_id = auth()->user()->id ?? null; // Lấy user ID từ session nếu có
            $momo_status = 0;
            $link_data = json_encode($request->all());

            // Lưu vào database
            DB::table('momos')->insert([
                'customer_id' => $customer_id,
                'momo_status' => $momo_status,
                'link_data' => $link_data,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('home')->with('success', 'Nạp momo thành công, vui lòng chờ Admin duyệt đơn.');
        }

        return redirect()->route('home')->with('error', 'Lỗi trong quá trình nạp Momo.');
    }

    public function momoIpn(Request $request)
    {
        // Xử lý IPN callback từ MoMo (tuỳ chỉnh theo yêu cầu)
        return response()->json(['message' => 'IPN received'], 200);
    }
}

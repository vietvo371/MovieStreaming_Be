<?php

namespace App\Http\Controllers;

use App\Models\GoiVip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{

    public function createVnpayPayment(Request $request)
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

            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $vnp_TmnCode = env('VNPAY_TMN_CODE', 'NJJ0R8FS');
            $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'BYKJBHPPZKQMKBIBGGXIYKWYFAYSJXCW');
            $vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
            $vnp_ReturnUrl = env('VNPAY_RETURN_URL') . $user->email;

            // Create payment data
            $vnp_TxnRef = time(); // Mã đơn hàng
            $vnp_OrderInfo = "Thanh toán gói VIP: " . $goiVip->ten_goi;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $goiVip->tien_sale * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $request->ip();

            // Set expire time
            $startTime = date("YmdHis");
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $expire
            ];

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            return response()->json([
                'status' => true,
                'payUrl' => $vnp_Url
            ]);

        } catch (\Exception $e) {
            Log::error('VNPAY payment error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function vnpayCallback(Request $request)
    {
        if($request->vnp_ResponseCode == "00") {
            $customer_id = auth()->user()->id ?? null;

            // Lưu vào database
            DB::table('vnpay_transactions')->insert([
                'customer_id' => $customer_id,
                'vnp_amount' => $request->vnp_Amount / 100,
                'vnp_txnref' => $request->vnp_TxnRef,
                'vnp_orderinfo' => $request->vnp_OrderInfo,
                'vnp_response_code' => $request->vnp_ResponseCode,
                'vnp_transaction_no' => $request->vnp_TransactionNo,
                'vnp_bank_code' => $request->vnp_BankCode,
                'vnp_payment_type' => $request->vnp_PaymentType,
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('home')->with('success', 'Thanh toán VNPAY thành công.');
        }

        return redirect()->route('home')->with('error', 'Lỗi trong quá trình thanh toán VNPAY.');
    }
}

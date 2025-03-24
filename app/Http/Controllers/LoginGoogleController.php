<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Carbon\Factory;
use Exception;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Tìm user theo email
            $user = KhachHang::where('email', $request->email)->first();
            $user->avatar = asset('uploads/avatars/admins/default_avatar.png');
            $user->save();

            // Nếu chưa có user thì tạo mới
            if (!$user) {
                $user = KhachHang::create([
                    'email' => $request->email,
                    'ho_ten' => $request->name,
                    'hinh_anh' => asset('uploads/avatars/admins/default_avatar.png'),
                    'google_id' => $request->google_id,
                    'password' => Hash::make(Str::random(16)), // Tạo password ngẫu nhiên
                    'is_block' => 0,
                ]);
            }

            // Tạo token cho user
            $token = $user->createToken('khach_hang_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'token' => $token,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đăng nhập thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
    public function loginGoogleApp(Request $request)
    {
        try {
            // Kiểm tra và xác thực yêu cầu
            // $request->validate([
            //     'id_token' => 'required|string',
            // ]);

            // Khởi tạo Google Client
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID_APP')]);

            // Xác minh ID token
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID token không hợp lệ'
                ], 401);
            }

            // Lấy thông tin từ payload
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];
            $picture = asset('uploads/avatars/admins/default_avatar.png');

            // Tìm người dùng theo google_id
            $user = KhachHang::where('google_id', $googleId)->first();

            if (!$user) {
                // Tìm người dùng theo email
                $user = KhachHang::where('email', $email)->first();

                if ($user) {
                    // Cập nhật google_id nếu tìm thấy người dùng qua email
                    $user->google_id = $googleId;
                    $user->avatar = $picture;
                    $user->save();
                } else {
                    // Tạo người dùng mới nếu không tìm thấy
                    $user = KhachHang::create([
                        'ho_va_ten' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $picture,
                        'so_dien_thoai' => '', // Có thể để trống hoặc cho giá trị mặc định
                        'is_active' => 1,
                        'password' => Hash::make("password123"), // Tạo mật khẩu ngẫu nhiên
                    ]);
                }
            }

            // Đăng nhập người dùng
            Auth::guard('khach_hang')->login($user);

            // Tạo token
            $token = $user->createToken('token_khach_hang')->plainTextToken;

            // Trả về thông tin người dùng và token
            return response()->json([
                'status' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->ho_va_ten,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

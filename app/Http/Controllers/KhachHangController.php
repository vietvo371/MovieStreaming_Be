<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class KhachHangController extends Controller
{
    public function getData()
    {
        $data   = KhachHang::select('khach_hangs.*')
                         ->get(); // get là ra 1 danh sách
        return response()->json([
            'khach_hang'  =>  $data,
        ]);
    }
    public function getDataProfile(Request $request){
        $user = KhachHang::where('id',$request->id_khach_hang)->first();
        return response()->json([
            'obj_user'  => $user,
        ]);
    }
    public function taoKhachHang(Request $request)
    {
        try {
            KhachHang::create([
                'email'                 =>$request->email,
                'ho_va_ten'             =>$request->ho_va_ten,
                'password'              =>bcrypt($request->password),
                'hinh_anh'              =>$request->hinh_anh,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => 'Bạn thêm khách hàng thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá khách hàng không thành công!!'
                ]);
        }

    }
    public function doiThongTin(Request $request)
    {
        try {
            KhachHang::where('id', $request->id)
                    ->update([
                        'email'                 =>$request->email,
                        'ho_va_ten'             =>$request->ho_va_ten,
                        'password'              =>($request->password),
                        'hinh_anh'              =>$request->hinh_anh,
                    ]);

            return response()->json([
                'status'     => true,
                'ho_ten_user'=> $request ->ho_va_ten,
                'message'    => 'Cập nhật tài khoản thành công!',
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập nhật tài khoản không thành công!!'
            ]);
        }
    }
    public function doiPass(Request $request)
    {
       $check = Auth::guard('khach_hang')->attempt(['email'=>$request->email,'password' =>$request->old_pass, ]);
        if ($check) {
            $user = Auth::guard('khach_hang')->user();
            $user->update([
                    'email'                 =>$request->email,
                    'ho_va_ten'             =>$request->ho_va_ten,
                    'password'              =>bcrypt($request->new_pass),
                    'hinh_anh'              =>$request->hinh_anh,
            ]);

            return response()->json([
                'message'   => 'Đổi mật khẩu thành công!!',
                'status'    => true,

            ]);
        }
        else {
            return response()->json([
                'message'   => 'Mật khẩu cũ không hợp lệ!!',
                'status'    => 'false'
            ]);
        }
    }

     public function timKhachHang(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = KhachHang::select('khach_hangs.*')
                    ->where('ho_va_ten', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'khach_hang'  =>  $data,
        ]);
    }
    public function xoaKhachHang($id)
    {
        try {
            KhachHang::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá khách hàng thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá khách hàng không thành công!!'
            ]);

        }

    }

    public function capnhatKhachHang(Request $request)
    {
        try {
            KhachHang::where('id', $request->id)
                    ->update([
                        'email'                 =>$request->email,
                        'ho_va_ten'             =>$request->ho_va_ten,
                        'password'              =>($request->password),
                        'hinh_anh'              =>$request->hinh_anh,
                    ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật thành ' . $request->email,
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Admin không thành công!!'
            ]);
        }
    }


    public function login(Request $request)
    {
        $check = Auth::guard('khach_hang')->attempt(['email'=>$request->email,'password' =>$request->password, ]);
        if ($check) {
            $user = Auth::guard('khach_hang')->user();
            return response()->json([
                'message'   => 'Đăng Nhập thành công!!',
                'status'    => true,
                'token'     => $user->createToken('api-token-khach')->plainTextToken,

            ]);
        }
        else {
            return response()->json([
                'message'   => 'Đăng Nhập không  thành công!!',
                'status'    => false
            ]);
        }
    }

    public function register(Request $request)
    {
        KhachHang::create([
            'email'         => $request->email,
            'ho_va_ten'     => $request->ho_va_ten,
            'password'      => bcrypt($request->password),
            'hinh_anh'      => $request->hinh_anh,
        ]);
        return response()->json([
            'message'   => 'Tạo tài khoản thành công!!',
            'status'    =>  true
        ]);
    }

    public function check(Request $request)
    {

        $user = Auth::guard('sanctum')->user();

        if($user)
        {
            $agent   = new Agent();
            $device  = $agent->device();
            $os      = $agent->platform();
            $browser = $agent->browser();
            DB::table('personal_access_tokens')
            ->where('id',$user->currentAccessToken()->id)
            ->update([
                'ip'            =>  request()->ip(),
                'device'        =>  $device,
                'os'            =>  $os,
                'trinh_duyet'   =>  $browser
            ]);
            return response()->json([
                'email'                => $user ->email,
                'id_user'              => $user ->id,
                'ho_ten_user'          => $user ->ho_va_ten,
                'hinh_anh_user'        => $user ->hinh_anh,
                'list'                 => $user ->tokens,

            ],200);
        }
        else
        {
            return response()->json([
                'message'   => 'Bạn cần đăng nhập hệ thống !!',
                'status'    => false
            ],401);
        }
    }
    public function xoatoken($id)
    {
        try {
            DB::table('personal_access_tokens')
            ->where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá token thành công!!'
            ]);
        } catch (ExceptionEvent  $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Nha token không thành công!!'
            ]);

        }

    }
}

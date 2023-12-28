<?php

namespace App\Http\Controllers;

use App\Models\AdminAnime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class AdminAnimeController extends Controller
{
    public function getData()
    {
        $data   = AdminAnime::select('admin_animes.*')
                         ->get(); // get là ra 1 danh sách
        return response()->json([
            'admin'  =>  $data,
        ]);
    }
    public function taoAdmin(Request $request)
    {
        try {
            AdminAnime::create([
                'email'                 =>$request->email,
                'ho_va_ten'             =>$request->ho_va_ten,
                'password'              =>bcrypt($request->password),
                'hinh_anh'              =>$request->hinh_anh,
                ]);
                return response()->json([
                    'status'   => true ,
                    'message'  => 'Bạn thêm admin thành công!',
                ]);
        } catch (ExceptionEvent $e) {
                return response()->json([
                    'status'     => false,
                    'message'    => 'Xoá admin không thành công!!'
                ]);
        }

    }

     public function timAdmin(Request $request)
    {
        $key    = '%'. $request->key . '%';
        $data   = AdminAnime::select('admin_animes.*')
                    ->where('ho_va_ten', 'like', $key)
                    ->get(); // get là ra 1 danh sách
        return response()->json([
        'admin'  =>  $data,
        ]);
    }
    public function xoaAdmin($id)
    {
        try {
            AdminAnime::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Admin thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Admin không thành công!!'
            ]);

        }

    }

    public function capnhatAdmin(Request $request)
    {
        try {
            AdminAnime::where('id', $request->id)
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

    public function thaydoiTrangThaiAdmin(Request $request)
    {

        try {
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            AdminAnime::where('id', $request->id)
                    ->update([
                        'tinh_trang'    =>$tinh_trang_moi
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
    public function login(Request $request)
    {
        $check = Auth::guard('admin')->attempt(['email'=>$request->email,'password' =>$request->password, ]);
        if ($check) {
            $user = Auth::guard('admin')->user();
            return response()->json([
                'message'   => 'Đăng Nhập thành công!!',
                'status'    => true,
                'token'     => $user->createToken('api-token-admin')->plainTextToken,

            ]);
        }
        else {
            return response()->json([
                'message'   => 'Đăng Nhập không  thành công!!',
                'status'    => 'false'
            ]);
        }
    }

    public function register(Request $request)
    {
        AdminAnime::create([
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
                'email'      => $user ->email,
                'ho_ten'     => $user ->ho_va_ten,
                'hinh_anh'   => $user ->hinh_anh,
                'list'       => $user ->tokens,

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

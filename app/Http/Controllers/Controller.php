<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function isUser()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user instanceof \App\Models\KhachHang) {
            return $user;
        }
        return false;
    }

    public function isAdmin()
    {
        $user = Auth::guard('sanctum')->user();

        if ($user instanceof \App\Models\AdminAnime) {
            return $user;
        }
        return false;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;
    protected $talbe = 'bai_viets';
    protected $fillable = [
        'tieu_de',
        'slug_tieu_de',
        'hinh_anh',
        'mo_ta',
        'mo_ta_chi_tiet',
        'id_chuyen_muc',
        'tinh_trang',
    ];
}

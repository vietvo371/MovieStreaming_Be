<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuanBaiViet extends Model
{
    use HasFactory;
    protected $table = 'binh_luan_bai_viets';
    protected $fillable = [
        'noi_dung',
        'id_bai_viet',
        'id_khach_hang',
    ];
}

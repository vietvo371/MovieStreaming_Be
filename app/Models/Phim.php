<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phim extends Model
{
    use HasFactory;
    protected $table = 'phims';
    protected $fillable = [
            'ten_phim',
            'slug_phim',
            'hinh_anh',
            'mo_ta',
            'id_loai_phim',
            'id_the_loai',
            'id_tac_gia',
            'so_tap_phim',
            'tinh_trang',
    ];

}

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
            'hinh_anh',
            'mo_ta',
            'id_loai_phim',
            'url',
            'id_the_loai',
            'tinh_trang_yeu_thich',
            'tinh_trang',
    ];

}

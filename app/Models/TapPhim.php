<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapPhim extends Model
{
    use HasFactory;
    protected $table = 'tap_phims';
    protected $fillable = [
       'ten_tap_phim',
       'slug_tap_phim',
       'url',
      'id_phim',
      'tinh_trang',
    ];
}

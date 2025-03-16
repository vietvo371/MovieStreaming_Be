<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoDich extends Model
{
    use HasFactory;
    protected $table = 'giao_diches';
    protected $fillable = [
        'id_Khach_hang',
        'ma_giao_dich',
        'orderInfo',
        'transactionNo',
        'paymentType',
        'responseCode',
        'transactionStatus',
        'tinh_trang'
    ];
}

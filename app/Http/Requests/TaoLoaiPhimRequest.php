<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaoLoaiPhimRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Update if authorization is required
    }

    public function rules()
    {
        return [
            'ten_loai_phim'    => 'required|string|max:255',
            'slug_loai_phim'   => 'required|string|max:255|unique:loai_phims,slug_loai_phim',
            'tinh_trang'       => 'required|boolean',
            'id_danh_muc'      => 'required|integer|exists:danh_muc_webs,id',
        ];
    }

    public function messages()
    {
        return [
            'ten_loai_phim.required'    => 'Tên loại phim là bắt buộc.',
            'slug_loai_phim.required'   => 'Slug loại phim là bắt buộc.',
            'slug_loai_phim.unique'     => 'Slug loại phim đã tồn tại.',
            'tinh_trang.required'       => 'Tình trạng là bắt buộc.',
            'id_danh_muc.required'      => 'Danh mục là bắt buộc.',
            'id_danh_muc.exists'        => 'Danh mục không tồn tại.',
        ];
    }
}

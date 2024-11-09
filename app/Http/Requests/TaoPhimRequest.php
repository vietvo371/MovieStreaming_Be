<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaoPhimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ten_phim'          => 'required|string|max:255',
            'slug_phim'         => 'required|string|max:255|unique:phims,slug_phim',
            'hinh_anh'          => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mo_ta'             => 'required|string',
            'thoi_gian_chieu'   => 'required|integer|min:1',
            'nam_san_xuat'      => 'required|integer|digits:4',
            'quoc_gia'          => 'required|string|max:255',
            'id_loai_phim'      => 'required|integer|exists:loai_phims,id',
            'dao_dien'          => 'required|string|max:255',
            'so_tap_phim'       => 'required|integer|min:1',
            'tinh_trang'        => 'required|boolean',
            'cong_ty_san_xuat'  => 'required|string|max:255',
            'the_loais'         => 'required|string' // Assuming this is a comma-separated string
        ];
    }

    public function messages()
    {
        return [
            'ten_phim.required'         => 'Tên phim là bắt buộc.',
            'ten_phim.max'              => 'Tên phim không được vượt quá 255 ký tự.',
            'slug_phim.required'        => 'Slug phim là bắt buộc.',
            'slug_phim.max'             => 'Slug phim không được vượt quá 255 ký tự.',
            'slug_phim.unique'          => 'Slug phim này đã tồn tại. Vui lòng chọn slug khác.',
            'hinh_anh.image'            => 'Hình ảnh phải là một file ảnh.',
            'hinh_anh.mimes'            => 'Hình ảnh phải có định dạng jpeg, png, jpg, hoặc gif.',
            'hinh_anh.max'              => 'Kích thước của hình ảnh không được vượt quá 2MB.',
            'hinh_anh.required'         => 'Hình ảnh là bắt buộc',
            'mo_ta.required'            => 'Mô tả là bắt buộc.',
            'thoi_gian_chieu.required'  => 'Thời gian chiếu là bắt buộc.',
            'thoi_gian_chieu.integer'   => 'Thời gian chiếu phải là một số nguyên.',
            'thoi_gian_chieu.min'       => 'Thời gian chiếu phải lớn hơn 0.',
            'nam_san_xuat.required'     => 'Năm sản xuất là bắt buộc.',
            'nam_san_xuat.integer'      => 'Năm sản xuất phải là số nguyên.',
            'nam_san_xuat.digits'       => 'Năm sản xuất phải có đúng 4 chữ số.',
            'quoc_gia.required'         => 'Quốc gia là bắt buộc.',
            'quoc_gia.max'              => 'Quốc gia không được vượt quá 255 ký tự.',
            'id_loai_phim.required'     => 'Loại phim là bắt buộc.',
            'id_loai_phim.exists'       => 'Loại phim không hợp lệ.',
            'dao_dien.required'         => 'Đạo diễn là bắt buộc.',
            'dao_dien.max'              => 'Tên đạo diễn không được vượt quá 255 ký tự.',
            'so_tap_phim.required'      => 'Số tập phim là bắt buộc.',
            'so_tap_phim.integer'       => 'Số tập phim phải là một số nguyên.',
            'so_tap_phim.min'           => 'Số tập phim phải lớn hơn 0.',
            'tinh_trang.required'       => 'Tình trạng là bắt buộc.',
            'tinh_trang.boolean'        => 'Tình trạng phải là true hoặc false.',
            'cong_ty_san_xuat.max'      => 'Tên công ty sản xuất không được vượt quá 255 ký tự.',
            'cong_ty_san_xuat.required' => 'Tên công ty sản xuất là bắt buộc',
            'the_loais.required'        => 'Thể loại là bắt buộc.',
        ];
    }
}

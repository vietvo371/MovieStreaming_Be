<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapNhatPhimRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can add any authorization logic here if needed
    }

    public function rules()
    {
        return [
            'id'                => 'required|exists:phims,id',
            'ten_phim'          => 'required|max:255',
            'slug_phim'         => 'required|max:255|unique:phims,slug_phim,' . $this->id,
            'mo_ta'             => 'nullable|max:1000',
            'thoi_gian_chieu'   => 'nullable|integer|min:0',
            'nam_san_xuat'      => 'nullable|integer|digits:4',
            'quoc_gia'          => 'nullable|max:255',
            'id_loai_phim'      => 'required|exists:loai_phims,id',
            'dao_dien'          => 'nullable|max:255',
            'so_tap_phim'       => 'nullable|integer|min:1',
            'tinh_trang'        => 'required|boolean',
            'cong_ty_san_xuat'  => 'nullable|max:255',
            'the_loais'         => 'required|string', // Comma separated list of genre IDs
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'               => 'ID phim là bắt buộc.',
            'id.exists'                 => 'Phim không tồn tại.',
            'ten_phim.required'         => 'Tên phim là bắt buộc.',
            'ten_phim.max'              => 'Tên phim không được vượt quá 255 ký tự.',
            'slug_phim.required'        => 'Slug phim là bắt buộc.',
            'slug_phim.max'             => 'Slug phim không được vượt quá 255 ký tự.',
            'slug_phim.unique'          => 'Slug phim đã tồn tại.',
            'hinh_anh.image'            => 'Hình ảnh phải là file ảnh.',
            'hinh_anh.mimes'            => 'Hình ảnh phải có định dạng jpeg, png, jpg, hoặc gif.',
            'hinh_anh.max'              => 'Hình ảnh không được vượt quá 2MB.',
            'mo_ta.max'                 => 'Mô tả không được vượt quá 1000 ký tự.',
            'thoi_gian_chieu.integer'   => 'Thời gian chiếu phải là một số nguyên.',
            'thoi_gian_chieu.min'       => 'Thời gian chiếu phải lớn hơn 0.',
            'nam_san_xuat.integer'      => 'Năm sản xuất phải là một số nguyên.',
            'nam_san_xuat.digits'       => 'Năm sản xuất phải có đúng 4 chữ số.',
            'quoc_gia.max'              => 'Quốc gia không được vượt quá 255 ký tự.',
            'id_loai_phim.required'     => 'Loại phim là bắt buộc.',
            'id_loai_phim.exists'       => 'Loại phim không hợp lệ.',
            'dao_dien.max'              => 'Tên đạo diễn không được vượt quá 255 ký tự.',
            'so_tap_phim.integer'       => 'Số tập phim phải là một số nguyên.',
            'so_tap_phim.min'           => 'Số tập phim phải lớn hơn 0.',
            'tinh_trang.required'       => 'Tình trạng là bắt buộc.',
            'tinh_trang.boolean'        => 'Tình trạng phải là true hoặc false.',
            'cong_ty_san_xuat.max'      => 'Tên công ty sản xuất không được vượt quá 255 ký tự.',
            'the_loais.required'        => 'Thể loại là bắt buộc.',
            'the_loais.string'          => 'Thể loại phải là một chuỗi (danh sách các ID thể loại).',
        ];
    }
}

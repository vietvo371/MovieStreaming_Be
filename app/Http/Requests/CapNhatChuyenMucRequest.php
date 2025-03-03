<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapNhatChuyenMucRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:chuyen_mucs,id',
            'ten_chuyen_muc'  => 'required|string|max:255|unique:chuyen_mucs,ten_chuyen_muc,' . $this->id,
            'slug_chuyen_muc' => 'required|string|max:255|unique:chuyen_mucs,slug_chuyen_muc,' . $this->id,
            'tinh_trang'      => 'required|boolean',
        ];
    }
    public function messages()
    {
        return [
            'id.required'               => 'ID chuyên mục là bắt buộc.',
            'id.exists'                 => 'ID chuyên mục không tồn tại.',
            'ten_chuyen_muc.required'   => 'Tên chuyên mục là bắt buộc.',
            'ten_chuyen_muc.unique'     => 'Tên chuyên mục đã tồn tại.',
            'slug_chuyen_muc.required'  => 'Slug chuyên mục là bắt buộc.',
            'slug_chuyen_muc.unique'    => 'Slug chuyên mục đã tồn tại.',
            'tinh_trang.required'       => 'Tình trạng là bắt buộc.',
            'tinh_trang.boolean'        => 'Tình trạng phải là true hoặc false.',
        ];
    }
}

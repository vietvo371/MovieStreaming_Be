<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaoTapPhimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:phims,id',  // Validate the film exists in the database
            'so_tap' => 'required|integer|min:1',  // Ensure the episode number is an integer and greater than 0
            'url' => 'required|url',  // Validate that the URL is valid
            'tinh_trang' => 'required|boolean',  // Validate status (active or inactive)
        ];
    }

    /**
     * Get the custom messages for the validator.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'Phim ID là bắt buộc.',
            'id.exists' => 'Phim không tồn tại.',
            'so_tap.required' => 'Số tập là bắt buộc.',
            'so_tap.integer' => 'Số tập phải là một số nguyên.',
            'so_tap.min' => 'Số tập phải lớn hơn 0.',
            'url.required' => 'URL là bắt buộc.',
            'url.url' => 'URL không hợp lệ.',
            'tinh_trang.required' => 'Tình trạng là bắt buộc.',
            'tinh_trang.boolean' => 'Tình trạng phải là 0 hoặc 1',
        ];
    }
}

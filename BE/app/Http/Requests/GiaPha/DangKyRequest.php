<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class DangKyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dungs,email',
            'mat_khau' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Họ tên không được để trống!',
            'email.required' => 'Email không được để trống!',
            'email.email' => 'Email không đúng định dạng!',
            'email.unique' => 'Email đã tồn tại!',
            'mat_khau.required' => 'Mật khẩu không được để trống!',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự!',
        ];
    }
}

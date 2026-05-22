<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuNguoiDungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:nguoi_dungs,id' : 'nullable|exists:nguoi_dungs,id',
            'ho_ten' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('nguoi_dungs', 'email')->ignore($this->id)],
            'mat_khau' => [$this->id ? 'nullable' : 'required', 'string', 'min:8'],
            'vai_tro' => ['required', Rule::in(['thanh_vien', 'truong_nhanh', 'quan_tri_vien'])],
            'trang_thai' => ['required', Rule::in(['hoat_dong', 'bi_khoa'])],
            'id_thanh_vien_gia_pha' => 'nullable|exists:thanh_vien_gia_phas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Họ tên không được để trống!',
            'email.required' => 'Email không được để trống!',
            'email.email' => 'Email không đúng định dạng!',
            'email.unique' => 'Email đã tồn tại trong hệ thống!',
            'mat_khau.required' => 'Mật khẩu không được để trống!',
            'mat_khau.min' => 'Mật khẩu tối thiểu 8 ký tự!',
            'vai_tro.required' => 'Vai trò không được để trống!',
        ];
    }
}

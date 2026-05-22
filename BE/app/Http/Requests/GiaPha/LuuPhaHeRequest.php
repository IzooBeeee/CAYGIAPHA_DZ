<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuPhaHeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:pha_hes,id' : 'nullable|exists:pha_hes,id',
            'ten_pha_he' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'id_nguoi_sang_lap' => 'nullable|exists:thanh_vien_gia_phas,id',
            'doi_hien_tai' => 'nullable|integer|min:1',
            'trang_thai' => ['required', Rule::in(['hoat_dong', 'an'])],
        ];
    }

    public function messages(): array
    {
        return [
            'ten_pha_he.required' => 'Tên phả hệ không được để trống!',
            'trang_thai.required' => 'Trạng thái không được để trống!',
        ];
    }
}

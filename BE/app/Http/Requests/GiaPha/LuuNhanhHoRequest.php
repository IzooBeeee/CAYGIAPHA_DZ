<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class LuuNhanhHoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:nhanh_hos,id' : 'nullable|exists:nhanh_hos,id',
            'id_pha_he' => 'required|exists:pha_hes,id',
            'ten_nhanh' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'id_nguoi_goc' => 'nullable|exists:thanh_vien_gia_phas,id',
            'id_truong_nhanh_hien_tai' => 'nullable|exists:thanh_vien_gia_phas,id',
            'id_nguoi_quan_ly' => 'nullable|exists:nguoi_dungs,id',
            'id_nhanh_cha' => 'nullable|exists:nhanh_hos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_pha_he.required' => 'Vui lòng chọn phả hệ!',
            'ten_nhanh.required' => 'Tên nhánh họ không được để trống!',
        ];
    }
}

<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuBaiVietRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:bai_viets,id' : 'nullable|exists:bai_viets,id',
            'id_nhanh_ho' => 'nullable|exists:nhanh_hos,id',
            'tieu_de' => 'required|string|max:255',
            'duong_dan' => ['nullable', 'string', 'max:255', Rule::unique('bai_viets', 'duong_dan')->ignore($this->id)],
            'noi_dung' => 'nullable|string',
            'anh_dai_dien' => 'nullable|image|max:2048',
            'trang_thai' => ['required', Rule::in(['ban_nhap', 'cong_khai', 'an'])],
        ];
    }
}

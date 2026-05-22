<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuHonNhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:hon_nhans,id' : 'nullable|exists:hon_nhans,id',
            'id_chong' => 'nullable|different:id_vo|exists:thanh_vien_gia_phas,id',
            'id_vo' => 'nullable|exists:thanh_vien_gia_phas,id',
            'ngay_ket_hon' => 'nullable|date',
            'ngay_ly_hon' => 'nullable|date|after_or_equal:ngay_ket_hon',
            'trang_thai' => ['required', Rule::in(['dang_ket_hon', 'da_ly_hon', 'goa_vo_chong'])],
            'ghi_chu' => 'nullable|string',
        ];
    }
}

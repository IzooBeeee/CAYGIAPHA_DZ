<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuSuKienDongHoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:su_kien_dong_hos,id' : 'nullable|exists:su_kien_dong_hos,id',
            'id_nhanh_ho' => 'nullable|exists:nhanh_hos,id',
            'tieu_de' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'thoi_gian' => 'nullable|date',
            'dia_diem' => 'nullable|string|max:255',
            'loai_su_kien' => ['required', Rule::in(['gio_chap', 'hop_ho', 'dam_cuoi', 'sinh_nhat', 'khac'])],
        ];
    }
}

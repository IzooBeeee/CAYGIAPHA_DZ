<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class LuuMoPhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:mo_phans,id' : 'nullable|exists:mo_phans,id',
            'id_thanh_vien_gia_pha' => 'required|exists:thanh_vien_gia_phas,id',
            'dia_chi_mo' => 'nullable|string|max:255',
            'toa_do_lat' => 'nullable|numeric|between:-90,90',
            'toa_do_lng' => 'nullable|numeric|between:-180,180',
            'ngay_an_tang' => 'nullable|date',
            'hinh_anh' => 'nullable|image|max:4096',
            'ghi_chu' => 'nullable|string',
        ];
    }
}

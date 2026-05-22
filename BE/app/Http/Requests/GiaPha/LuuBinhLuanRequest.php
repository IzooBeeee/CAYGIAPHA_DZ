<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class LuuBinhLuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_bai_viet' => 'required|exists:bai_viets,id',
            'noi_dung' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'noi_dung.required' => 'Noi dung binh luan khong duoc de trong!',
            'noi_dung.max' => 'Noi dung binh luan khong duoc qua 2000 ky tu!',
        ];
    }
}

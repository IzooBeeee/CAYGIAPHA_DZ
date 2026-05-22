<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class XoaSuKienDongHoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['id' => 'required|exists:su_kien_dong_hos,id'];
    }
}

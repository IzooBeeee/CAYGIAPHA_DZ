<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class XoaMoPhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['id' => 'required|exists:mo_phans,id'];
    }
}

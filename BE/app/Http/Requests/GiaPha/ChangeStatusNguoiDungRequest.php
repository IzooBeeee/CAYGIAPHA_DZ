<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusNguoiDungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['id' => 'required|exists:nguoi_dungs,id'];
    }
}

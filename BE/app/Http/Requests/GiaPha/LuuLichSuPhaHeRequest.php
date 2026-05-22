<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;

class LuuLichSuPhaHeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:lich_su_pha_hes,id' : 'nullable|exists:lich_su_pha_hes,id',
            'id_pha_he' => 'required|exists:pha_hes,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'nullable|string',
            'moc_thoi_gian' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id_pha_he.required' => 'Vui long chon pha he!',
            'tieu_de.required' => 'Tieu de khong duoc de trong!',
        ];
    }
}

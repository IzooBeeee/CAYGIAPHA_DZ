<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuTepTinTuLieuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:tep_tin_tu_lieus,id' : 'nullable|exists:tep_tin_tu_lieus,id',
            'id_thanh_vien_gia_pha' => 'nullable|exists:thanh_vien_gia_phas,id',
            'id_nhanh_ho' => 'nullable|exists:nhanh_hos,id',
            'ten_tep' => 'required|string|max:255',
            'duong_dan_tep' => [$this->id ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,mp4', 'max:10240'],
            'loai_tep' => ['required', Rule::in(['anh', 'pdf', 'word', 'video', 'khac'])],
            'mo_ta' => 'nullable|string',
        ];
    }
}

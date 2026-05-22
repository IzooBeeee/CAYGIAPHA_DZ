<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuCayGiaPhaChiaSeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_nhanh_ho' => 'nullable|exists:nhanh_hos,id',
            'pham_vi' => ['required', Rule::in(['cong_khai', 'rieng_tu', 'co_mat_khau'])],
            'mat_khau' => 'nullable|string|min:4|max:100',
            'ngay_het_han' => 'nullable|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'pham_vi.required' => 'Pham vi chia se khong duoc de trong!',
            'mat_khau.min' => 'Mat khau phai co it nhat 4 ky tu!',
            'ngay_het_han.after' => 'Ngay het han phai la ngay trong tuong lai!',
        ];
    }
}

<?php

namespace App\Http\Requests\GiaPha;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LuuThanhVienGiaPhaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => str_ends_with($this->path(), 'update') || str_ends_with($this->path(), 'cap-nhat') ? 'required|exists:thanh_vien_gia_phas,id' : 'nullable|exists:thanh_vien_gia_phas,id',
            'id_nhanh_ho' => 'nullable|exists:nhanh_hos,id',
            'ho_ten' => 'required|string|max:255',
            'ten_khac' => 'nullable|string|max:255',
            'gioi_tinh' => ['required', Rule::in(['nam', 'nu', 'khac'])],
            'ngay_sinh' => 'nullable|date',
            'ngay_mat' => 'nullable|date|after_or_equal:ngay_sinh',
            'con_song' => 'nullable|boolean',
            'noi_sinh' => 'nullable|string|max:255',
            'que_quan' => 'nullable|string|max:255',
            'dia_chi_hien_tai' => 'nullable|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:30',
            'anh_dai_dien' => 'nullable|image|max:2048',
            'doi_thu' => 'nullable|integer|min:1',
            'id_cha' => 'nullable|different:id|exists:thanh_vien_gia_phas,id',
            'id_me' => 'nullable|different:id|exists:thanh_vien_gia_phas,id',
            'tieu_su' => 'nullable|string',
            'ghi_chu' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Họ tên thành viên không được để trống!',
            'gioi_tinh.required' => 'Giới tính không được để trống!',
            'ngay_mat.after_or_equal' => 'Ngày mất không được nhỏ hơn ngày sinh!',
            'id_cha.different' => 'Cha không được là chính thành viên này!',
            'id_me.different' => 'Mẹ không được là chính thành viên này!',
            'anh_dai_dien.image' => 'Ảnh đại diện phải là file hình ảnh!',
            'anh_dai_dien.max' => 'Ảnh đại diện tối đa 2MB!',
        ];
    }
}

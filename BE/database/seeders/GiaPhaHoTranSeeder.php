<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChiNhanh;
use App\Models\ThanhVien;
use App\Models\VoChong;
use App\Models\SuKien;
use App\Models\DoiTocHo;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class GiaPhaHoTranSeeder extends Seeder
{
    public function run()
    {
        // 1. Tạo tài khoản Admin mẫu
        NguoiDung::create([
            'ho_ten' => 'Quản trị viên Họ Trần',
            'email' => 'admin@hotran.com',
            'mat_khau' => Hash::make('123456'),
            'vai_tro' => 'Admin',
            'trang_thai' => 'Hoạt động'
        ]);

        // 2. Tạo Chi Nhánh
        $cnHanoi = ChiNhanh::create(['ten_chi' => 'Chi Họ Trần - Hà Nội', 'mo_ta' => 'Chi trưởng tại Hà Nội']);
        $cnNamDinh = ChiNhanh::create(['ten_chi' => 'Chi Họ Trần - Nam Định', 'mo_ta' => 'Chi thứ tại Nam Định']);

        // 3. Tạo Đời Tộc Họ
        DoiTocHo::create(['so_doi' => 1, 'ten_doi' => 'Đời thứ nhất (Thủy tổ)', 'mo_ta' => 'Thủy tổ khai sáng dòng họ']);
        DoiTocHo::create(['so_doi' => 2, 'ten_doi' => 'Đời thứ hai', 'mo_ta' => 'Thế hệ thứ hai']);
        DoiTocHo::create(['so_doi' => 3, 'ten_doi' => 'Đời thứ ba', 'mo_ta' => 'Thế hệ thứ ba']);

        // 4. Tạo Thành Viên (Cây gia phả mẫu)
        // Đời 1
        $ongNoi = ThanhVien::create([
            'ho_ten' => 'Trần Cảnh',
            'ten_goi' => 'Cụ Cảnh',
            'gioi_tinh' => 'Nam',
            'ngay_sinh' => '1920-01-01',
            'ngay_mat' => '1995-12-31',
            'doi_thu' => 1,
            'chi_nhanh_id' => $cnHanoi->id,
            'trang_thai' => 'Đã mất'
        ]);

        $baNoi = ThanhVien::create([
            'ho_ten' => 'Lê Thị Hoa',
            'gioi_tinh' => 'Nữ',
            'ngay_sinh' => '1925-05-15',
            'ngay_mat' => '2000-06-20',
            'doi_thu' => 1,
            'chi_nhanh_id' => $cnHanoi->id,
            'trang_thai' => 'Đã mất'
        ]);

        // Kết hôn đời 1
        VoChong::create([
            'chong_id' => $ongNoi->id,
            'vo_id' => $baNoi->id,
            'ngay_cuoi' => '1945-10-10',
            'trang_thai' => 'Qua đời'
        ]);

        // Đời 2
        $bo = ThanhVien::create([
            'ho_ten' => 'Trần Văn Hùng',
            'ten_goi' => 'Ông Hùng',
            'gioi_tinh' => 'Nam',
            'ngay_sinh' => '1955-08-12',
            'doi_thu' => 2,
            'chi_nhanh_id' => $cnHanoi->id,
            'cha_id' => $ongNoi->id,
            'me_id' => $baNoi->id,
            'trang_thai' => 'Còn sống'
        ]);

        $me = ThanhVien::create([
            'ho_ten' => 'Nguyễn Thị Lan',
            'gioi_tinh' => 'Nữ',
            'ngay_sinh' => '1960-03-25',
            'doi_thu' => 2,
            'chi_nhanh_id' => $cnHanoi->id,
            'trang_thai' => 'Còn sống'
        ]);

        VoChong::create([
            'chong_id' => $bo->id,
            'vo_id' => $me->id,
            'ngay_cuoi' => '1980-02-14',
            'trang_thai' => 'Đang sống'
        ]);

        // Đời 3
        ThanhVien::create([
            'ho_ten' => 'Trần Minh Quân',
            'ten_goi' => 'Quân',
            'gioi_tinh' => 'Nam',
            'ngay_sinh' => '1990-11-20',
            'doi_thu' => 3,
            'chi_nhanh_id' => $cnHanoi->id,
            'cha_id' => $bo->id,
            'me_id' => $me->id,
            'trang_thai' => 'Còn sống'
        ]);

        ThanhVien::create([
            'ho_ten' => 'Trần Thu Hà',
            'ten_goi' => 'Hà',
            'gioi_tinh' => 'Nữ',
            'ngay_sinh' => '1995-07-05',
            'doi_thu' => 3,
            'chi_nhanh_id' => $cnHanoi->id,
            'cha_id' => $bo->id,
            'me_id' => $me->id,
            'trang_thai' => 'Còn sống'
        ]);

        // 5. Tạo Sự Kiện mẫu
        SuKien::create([
            'tieu_de' => 'Giỗ Tổ Họ Trần',
            'noi_dung' => 'Ngày giỗ tổ hằng năm tại nhà thờ tộc',
            'ngay_to_chuc' => date('Y') . '-03-15',
            'dia_diem' => 'Nam Định',
            'loai' => 'Giỗ tổ'
        ]);

        SuKien::create([
            'tieu_de' => 'Họp Mặt Đầu Năm',
            'noi_dung' => 'Gặp gỡ con cháu đầu xuân 2026',
            'ngay_to_chuc' => '2026-02-10',
            'dia_diem' => 'Hà Nội',
            'loai' => 'Họp họ'
        ]);
    }
}

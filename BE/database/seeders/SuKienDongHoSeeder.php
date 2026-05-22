<?php

namespace Database\Seeders;

use App\Models\SuKienDongHo;
use Illuminate\Database\Seeder;

class SuKienDongHoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        SuKienDongHo::insert([
            $this->suKien(1, null, 'Giỗ tổ họ Nguyễn Văn', 'Lễ giỗ tổ hằng năm của toàn dòng họ.', '2026-03-10 08:00:00', 'Nhà thờ họ Nguyễn Văn, Đà Nẵng', 'gio_chap', GiaPhaDemoIds::USER_ADMIN, $now),
            $this->suKien(2, null, 'Họp họ đầu năm', 'Tổng kết hoạt động và bàn kế hoạch năm mới.', '2026-02-22 09:00:00', 'Hội trường phường Hòa Cường', 'hop_ho', GiaPhaDemoIds::USER_ADMIN, $now),
            $this->suKien(3, GiaPhaDemoIds::NHANH_AN, 'Gặp mặt nhánh Nguyễn Văn An', 'Gặp mặt riêng của nhánh Nguyễn Văn An.', '2026-04-05 17:30:00', 'Nhà ông Nguyễn Văn Cường', 'hop_ho', GiaPhaDemoIds::USER_CUONG, $now),
            $this->suKien(4, GiaPhaDemoIds::NHANH_BINH, 'Gặp mặt nhánh Nguyễn Văn Bình', 'Gặp mặt riêng của nhánh Nguyễn Văn Bình.', '2026-04-12 17:30:00', 'Nhà ông Nguyễn Văn Hùng', 'hop_ho', GiaPhaDemoIds::USER_HUNG, $now),
            $this->suKien(5, GiaPhaDemoIds::NHANH_GOC, 'Sinh nhật thành viên cao tuổi', 'Chúc thọ các thành viên cao tuổi trong dòng họ.', '2026-06-01 18:00:00', 'Nhà văn hóa khu dân cư', 'sinh_nhat', GiaPhaDemoIds::USER_ADMIN, $now),
        ]);
    }

    private function suKien(int $id, ?int $idNhanhHo, string $tieuDe, string $moTa, string $thoiGian, string $diaDiem, string $loai, int $idNguoiTao, $now): array
    {
        return [
            'id' => $id,
            'id_nhanh_ho' => $idNhanhHo,
            'tieu_de' => $tieuDe,
            'mo_ta' => $moTa,
            'thoi_gian' => $thoiGian,
            'dia_diem' => $diaDiem,
            'loai_su_kien' => $loai,
            'id_nguoi_tao' => $idNguoiTao,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

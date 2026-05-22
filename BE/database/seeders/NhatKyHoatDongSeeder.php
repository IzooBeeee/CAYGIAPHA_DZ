<?php

namespace Database\Seeders;

use App\Models\NhatKyHoatDong;
use Illuminate\Database\Seeder;

class NhatKyHoatDongSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        NhatKyHoatDong::insert([
            $this->log(1, GiaPhaDemoIds::USER_ADMIN, 'them', 'pha_hes', GiaPhaDemoIds::PHA_HE, null, ['ten_pha_he' => 'Gia phả họ Nguyễn Văn'], $now),
            $this->log(2, GiaPhaDemoIds::USER_ADMIN, 'them', 'nhanh_hos', GiaPhaDemoIds::NHANH_GOC, null, ['ten_nhanh' => 'Nhánh gốc họ Nguyễn Văn'], $now),
            $this->log(3, GiaPhaDemoIds::USER_ADMIN, 'them', 'nhanh_hos', GiaPhaDemoIds::NHANH_AN, null, ['ten_nhanh' => 'Nhánh Nguyễn Văn An'], $now),
            $this->log(4, GiaPhaDemoIds::USER_ADMIN, 'them', 'thanh_vien_gia_phas', GiaPhaDemoIds::TV_TO, null, ['ho_ten' => 'Nguyễn Văn Tổ'], $now),
            $this->log(6, GiaPhaDemoIds::USER_CUONG, 'sua', 'thanh_vien_gia_phas', GiaPhaDemoIds::TV_DUC, ['so_dien_thoai' => '0905000001'], ['so_dien_thoai' => '0905123456'], $now),
            $this->log(7, GiaPhaDemoIds::USER_CUONG, 'sua', 'thanh_vien_gia_phas', GiaPhaDemoIds::TV_DUC, ['dia_chi' => 'Hà Nội'], ['dia_chi' => 'TP. Hồ Chí Minh'], $now),
            $this->log(8, GiaPhaDemoIds::USER_ADMIN, 'them', 'hon_nhans', 1, null, ['id_chong' => GiaPhaDemoIds::TV_TO, 'id_vo' => 1], $now),
            $this->log(9, GiaPhaDemoIds::USER_ADMIN, 'them', 'bai_viets', GiaPhaDemoIds::BAI_LICH_SU, null, ['tieu_de' => 'Lịch sử hình thành gia phả họ Nguyễn Văn'], $now),
            $this->log(10, GiaPhaDemoIds::USER_CUONG, 'them', 'su_kien_dong_hos', 3, null, ['tieu_de' => 'Gặp mặt nhánh Nguyễn Văn An'], $now),
            $this->log(11, GiaPhaDemoIds::USER_DUC, 'dang_nhap', 'nguoi_dungs', GiaPhaDemoIds::USER_DUC, null, ['email' => 'duc@gmail.com'], $now),
        ]);
    }

    private function log(int $id, ?int $idNguoiDung, string $hanhDong, string $tenBang, ?int $idBanGhi, ?array $duLieuCu, ?array $duLieuMoi, $now): array
    {
        return [
            'id' => $id,
            'id_nguoi_dung' => $idNguoiDung,
            'hanh_dong' => $hanhDong,
            'ten_bang' => $tenBang,
            'id_ban_ghi' => $idBanGhi,
            'du_lieu_cu' => $duLieuCu ? json_encode($duLieuCu, JSON_UNESCAPED_UNICODE) : null,
            'du_lieu_moi' => $duLieuMoi ? json_encode($duLieuMoi, JSON_UNESCAPED_UNICODE) : null,
            'dia_chi_ip' => '127.0.0.1',
            'trinh_duyet' => 'Demo Seeder',
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

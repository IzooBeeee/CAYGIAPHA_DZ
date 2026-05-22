<?php

namespace Database\Seeders;

use App\Models\TepTinTuLieu;
use Illuminate\Database\Seeder;

class TepTinTuLieuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        TepTinTuLieu::insert([
            $this->tepTin(1, null, GiaPhaDemoIds::NHANH_GOC, GiaPhaDemoIds::USER_ADMIN, 'Ảnh gia đình dòng họ', 'uploads/tu-lieu/anh-gia-dinh.jpg', 'anh', 'Ảnh chụp chung trong ngày họp họ.', $now),
            $this->tepTin(2, null, GiaPhaDemoIds::NHANH_GOC, GiaPhaDemoIds::USER_ADMIN, 'Bản scan gia phả cũ', 'uploads/tu-lieu/gia-pha-cu.pdf', 'pdf', 'Bản scan tài liệu gia phả được lưu lại từ đời trước.', $now),
            $this->tepTin(3, GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::NHANH_GOC, GiaPhaDemoIds::USER_ADMIN, 'Ảnh mộ tổ', 'uploads/tu-lieu/mo-to.jpg', 'anh', 'Ảnh khu mộ cụ Nguyễn Văn Tổ.', $now),
            $this->tepTin(4, null, GiaPhaDemoIds::NHANH_AN, GiaPhaDemoIds::USER_CUONG, 'Ảnh họp họ', 'uploads/tu-lieu/anh-hop-ho.jpg', 'anh', 'Ảnh buổi gặp mặt nhánh Nguyễn Văn An.', $now),
            $this->tepTin(5, null, null, GiaPhaDemoIds::USER_ADMIN, 'Tài liệu lịch sử dòng họ', 'uploads/tu-lieu/tai-lieu-lich-su-dong-ho.docx', 'word', 'Tài liệu tổng hợp các mốc lịch sử chính.', $now),
        ]);
    }

    private function tepTin(int $id, ?int $idThanhVien, ?int $idNhanhHo, int $idNguoiTaiLen, string $tenTep, string $duongDan, string $loai, string $moTa, $now): array
    {
        return [
            'id' => $id,
            'id_thanh_vien_gia_pha' => $idThanhVien,
            'id_nhanh_ho' => $idNhanhHo,
            'id_nguoi_tai_len' => $idNguoiTaiLen,
            'ten_tep' => $tenTep,
            'duong_dan_tep' => $duongDan,
            'loai_tep' => $loai,
            'mo_ta' => $moTa,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

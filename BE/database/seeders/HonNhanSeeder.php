<?php

namespace Database\Seeders;

use App\Models\HonNhan;
use Illuminate\Database\Seeder;

class HonNhanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        HonNhan::insert([
            $this->honNhan(1, GiaPhaDemoIds::TV_TO, GiaPhaDemoIds::TV_TRAN_THI_GOC, '1947-01-20', null, 'goa_vo_chong', 'Hôn nhân đời thứ nhất.', $now),
            $this->honNhan(2, GiaPhaDemoIds::TV_AN, GiaPhaDemoIds::TV_LE_THI_MAI, '1973-04-15', null, 'goa_vo_chong', 'Ông An đã mất năm 2020.', $now),
            $this->honNhan(3, GiaPhaDemoIds::TV_BINH, GiaPhaDemoIds::TV_PHAM_THI_DUNG, '1978-06-10', null, 'dang_ket_hon', null, $now),
            $this->honNhan(4, GiaPhaDemoIds::TV_CUONG, GiaPhaDemoIds::TV_VO_THI_THU, '1996-05-12', '2005-08-30', 'da_ly_hon', 'Vợ thứ nhất của Nguyễn Văn Cường.', $now),
            $this->honNhan(5, GiaPhaDemoIds::TV_CUONG, GiaPhaDemoIds::TV_DANG_THI_HANH, '2008-10-18', null, 'dang_ket_hon', 'Vợ thứ hai của Nguyễn Văn Cường.', $now),
            $this->honNhan(6, GiaPhaDemoIds::TV_TRAN_VAN_MINH, GiaPhaDemoIds::TV_LAN, '1998-03-20', '2008-07-12', 'da_ly_hon', 'Chồng thứ nhất của Nguyễn Thị Lan.', $now),
            $this->honNhan(7, GiaPhaDemoIds::TV_HOANG_VAN_PHUC, GiaPhaDemoIds::TV_LAN, '2010-01-22', null, 'dang_ket_hon', 'Chồng thứ hai của Nguyễn Thị Lan.', $now),
            $this->honNhan(8, GiaPhaDemoIds::TV_HUNG, GiaPhaDemoIds::TV_BUI_THI_TRANG, '2003-11-09', null, 'dang_ket_hon', null, $now),
            $this->honNhan(9, GiaPhaDemoIds::TV_DUC, GiaPhaDemoIds::TV_LE_THI_YEN, '2023-09-02', null, 'dang_ket_hon', null, $now),
        ]);
    }

    private function honNhan(int $id, int $idChong, int $idVo, ?string $ngayKetHon, ?string $ngayLyHon, string $trangThai, ?string $ghiChu, $now): array
    {
        return [
            'id' => $id,
            'id_chong' => $idChong,
            'id_vo' => $idVo,
            'ngay_ket_hon' => $ngayKetHon,
            'ngay_ly_hon' => $ngayLyHon,
            'trang_thai' => $trangThai,
            'ghi_chu' => $ghiChu,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

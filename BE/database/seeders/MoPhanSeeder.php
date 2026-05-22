<?php

namespace Database\Seeders;

use App\Models\MoPhan;
use Illuminate\Database\Seeder;

class MoPhanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        MoPhan::insert([
            $this->moPhan(1, GiaPhaDemoIds::TV_TO, 'Nghĩa trang gia tộc, Hòa Vang, Đà Nẵng', '16.0470790', '108.2062300', '1995-03-12', 'uploads/mo-phan/mo-to.jpg', 'Mộ thủy tổ được con cháu tu sửa hằng năm.', $now),
            $this->moPhan(2, GiaPhaDemoIds::TV_TRAN_THI_GOC, 'Nghĩa trang gia tộc, Hòa Vang, Đà Nẵng', '16.0475000', '108.2059000', '2000-07-22', null, 'Mộ nằm cạnh mộ cụ Nguyễn Văn Tổ.', $now),
            $this->moPhan(3, GiaPhaDemoIds::TV_AN, 'Nghĩa trang gia tộc, Hòa Vang, Đà Nẵng', '16.0481200', '108.2070100', '2020-09-20', null, 'Mộ người gốc nhánh Nguyễn Văn An.', $now),
        ]);
    }

    private function moPhan(int $id, int $idThanhVien, string $diaChi, string $lat, string $lng, string $ngayAnTang, ?string $hinhAnh, string $ghiChu, $now): array
    {
        return [
            'id' => $id,
            'id_thanh_vien_gia_pha' => $idThanhVien,
            'dia_chi_mo' => $diaChi,
            'toa_do_lat' => $lat,
            'toa_do_lng' => $lng,
            'ngay_an_tang' => $ngayAnTang,
            'hinh_anh' => $hinhAnh,
            'ghi_chu' => $ghiChu,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

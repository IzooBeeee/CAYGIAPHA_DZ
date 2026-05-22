<?php

namespace Database\Seeders;

use App\Models\BinhLuan;
use Illuminate\Database\Seeder;

class BinhLuanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        BinhLuan::insert([
            $this->binhLuan(1, GiaPhaDemoIds::USER_CUONG, GiaPhaDemoIds::BAI_LICH_SU, 'Bài viết rất hữu ích để con cháu hiểu về cội nguồn.', 'hien_thi', $now),
            $this->binhLuan(2, GiaPhaDemoIds::USER_HUNG, GiaPhaDemoIds::BAI_HIEU_HOC, 'Nhánh Nguyễn Văn Bình sẽ bổ sung thêm tư liệu học tập của các đời sau.', 'hien_thi', $now),
            $this->binhLuan(3, GiaPhaDemoIds::USER_DUC, GiaPhaDemoIds::BAI_NHANH_AN, 'Con đã xem và sẽ cập nhật thêm ảnh gia đình.', 'hien_thi', $now),
            $this->binhLuan(4, GiaPhaDemoIds::USER_NGOC, GiaPhaDemoIds::BAI_HOP_HO, 'Mong buổi họp họ năm nay có nhiều thành viên tham dự.', 'hien_thi', $now),
            $this->binhLuan(5, GiaPhaDemoIds::USER_LOCKED, GiaPhaDemoIds::BAI_NHANH_BINH, 'Bình luận này được ẩn để demo kiểm duyệt.', 'an', $now),
        ]);
    }

    private function binhLuan(int $id, int $idNguoiDung, int $idBaiViet, string $noiDung, string $trangThai, $now): array
    {
        return [
            'id' => $id,
            'id_nguoi_dung' => $idNguoiDung,
            'id_bai_viet' => $idBaiViet,
            'noi_dung' => $noiDung,
            'trang_thai' => $trangThai,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

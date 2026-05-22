<?php

namespace Database\Seeders;

use App\Models\BaiViet;
use Illuminate\Database\Seeder;

class BaiVietSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $noiDung = "Dòng họ lưu giữ nhiều câu chuyện về nguồn gốc, nề nếp gia đình và tinh thần tương trợ giữa các thế hệ.\n\nNội dung này dùng cho môi trường demo, giúp kiểm tra hiển thị bài viết, bình luận và phân quyền theo nhánh họ.";

        BaiViet::insert([
            $this->baiViet(GiaPhaDemoIds::BAI_LICH_SU, GiaPhaDemoIds::USER_ADMIN, null, 'Lịch sử hình thành gia phả họ Nguyễn Văn', 'lich-su-hinh-thanh-gia-pha-ho-nguyen-van', $noiDung, $now),
            $this->baiViet(GiaPhaDemoIds::BAI_HIEU_HOC, GiaPhaDemoIds::USER_ADMIN, null, 'Truyền thống hiếu học của dòng họ', 'truyen-thong-hieu-hoc-cua-dong-ho', $noiDung, $now),
            $this->baiViet(GiaPhaDemoIds::BAI_NHANH_AN, GiaPhaDemoIds::USER_CUONG, GiaPhaDemoIds::NHANH_AN, 'Thông tin nhánh Nguyễn Văn An', 'thong-tin-nhanh-nguyen-van-an', $noiDung, $now),
            $this->baiViet(GiaPhaDemoIds::BAI_NHANH_BINH, GiaPhaDemoIds::USER_HUNG, GiaPhaDemoIds::NHANH_BINH, 'Thông tin nhánh Nguyễn Văn Bình', 'thong-tin-nhanh-nguyen-van-binh', $noiDung, $now),
            $this->baiViet(GiaPhaDemoIds::BAI_HOP_HO, GiaPhaDemoIds::USER_ADMIN, null, 'Thông báo họp họ năm 2026', 'thong-bao-hop-ho-nam-2026', $noiDung, $now),
        ]);
    }

    private function baiViet(int $id, int $idNguoiDung, ?int $idNhanhHo, string $tieuDe, string $duongDan, string $noiDung, $now): array
    {
        return [
            'id' => $id,
            'id_nguoi_dung' => $idNguoiDung,
            'id_nhanh_ho' => $idNhanhHo,
            'tieu_de' => $tieuDe,
            'duong_dan' => $duongDan,
            'noi_dung' => $noiDung,
            'anh_dai_dien' => null,
            'trang_thai' => 'cong_khai',
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

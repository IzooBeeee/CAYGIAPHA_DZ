<?php

namespace Database\Seeders;

use App\Models\ThongBao;
use Illuminate\Database\Seeder;

class ThongBaoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        ThongBao::insert([
            $this->thongBao(1, GiaPhaDemoIds::USER_ADMIN, GiaPhaDemoIds::USER_CUONG, null, 'Sự kiện họp họ', 'Họp họ đầu năm sẽ diễn ra ngày 22/02/2026.', 'su_kien', false, $now),
            $this->thongBao(2, GiaPhaDemoIds::USER_ADMIN, GiaPhaDemoIds::USER_HUNG, null, 'Sự kiện họp họ', 'Họp họ đầu năm sẽ diễn ra ngày 22/02/2026.', 'su_kien', true, $now),
            $this->thongBao(3, GiaPhaDemoIds::USER_ADMIN, GiaPhaDemoIds::USER_DUC, null, 'Bài viết mới', 'Đã đăng bài Lịch sử hình thành gia phả họ Nguyễn Văn.', 'bai_viet', true, $now),
            $this->thongBao(4, GiaPhaDemoIds::USER_CUONG, GiaPhaDemoIds::USER_NGOC, GiaPhaDemoIds::NHANH_AN, 'Thông tin nhánh mới', 'Nhánh Nguyễn Văn An vừa cập nhật bài viết giới thiệu.', 'bai_viet', false, $now),
        ]);
    }

    private function thongBao(int $id, ?int $idNguoiGui, int $idNguoiNhan, ?int $idNhanhHo, string $tieuDe, string $noiDung, string $loai, bool $daDoc, $now): array
    {
        return [
            'id' => $id,
            'id_nguoi_gui' => $idNguoiGui,
            'id_nguoi_nhan' => $idNguoiNhan,
            'id_nhanh_ho' => $idNhanhHo,
            'tieu_de' => $tieuDe,
            'noi_dung' => $noiDung,
            'loai_thong_bao' => $loai,
            'da_doc' => $daDoc,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\LichSuPhaHe;
use Illuminate\Database\Seeder;

class LichSuPhaHeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        LichSuPhaHe::insert([
            $this->lichSu(1, 'Nguồn gốc dòng họ', 'Dòng họ Nguyễn Văn bắt nguồn từ một gia đình nông nghiệp, coi trọng đạo hiếu và sự gắn kết giữa các đời.', '1920-01-01', $now),
            $this->lichSu(2, 'Giai đoạn lập nghiệp tại Đà Nẵng', 'Cụ Nguyễn Văn Tổ cùng gia đình ổn định cuộc sống tại Đà Nẵng và xây dựng nền tảng cho các thế hệ sau.', '1950-01-01', $now),
            $this->lichSu(3, 'Giai đoạn phát triển các nhánh', 'Từ đời thứ hai, dòng họ hình thành các nhánh Nguyễn Văn An và Nguyễn Văn Bình để tiện quản lý gia phả.', '1980-01-01', $now),
            $this->lichSu(4, 'Hoạt động dòng họ hiện nay', 'Dòng họ duy trì họp họ, giỗ tổ, lưu trữ tư liệu và cập nhật cây gia phả bằng hệ thống số.', '2026-01-01', $now),
        ]);
    }

    private function lichSu(int $id, string $tieuDe, string $noiDung, string $mocThoiGian, $now): array
    {
        return [
            'id' => $id,
            'id_pha_he' => GiaPhaDemoIds::PHA_HE,
            'tieu_de' => $tieuDe,
            'noi_dung' => $noiDung,
            'moc_thoi_gian' => $mocThoiGian,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}

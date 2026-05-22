<?php

namespace Database\Seeders;

use App\Models\NhanhHo;
use Illuminate\Database\Seeder;

class NhanhHoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        NhanhHo::insert([
            [
                'id' => GiaPhaDemoIds::NHANH_GOC,
                'id_pha_he' => GiaPhaDemoIds::PHA_HE,
                'ten_nhanh' => 'Nhánh gốc họ Nguyễn Văn',
                'mo_ta' => 'Nhánh gốc bắt đầu từ cụ Nguyễn Văn Tổ.',
                'id_nguoi_goc' => GiaPhaDemoIds::TV_TO,
                'id_truong_nhanh_hien_tai' => GiaPhaDemoIds::TV_BINH,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::NHANH_AN,
                'id_pha_he' => GiaPhaDemoIds::PHA_HE,
                'ten_nhanh' => 'Nhánh Nguyễn Văn An',
                'mo_ta' => 'Nhánh đời thứ hai do ông Nguyễn Văn An là người gốc nhánh.',
                'id_nguoi_goc' => GiaPhaDemoIds::TV_AN,
                'id_truong_nhanh_hien_tai' => GiaPhaDemoIds::TV_CUONG,
                'id_nguoi_quan_ly' => GiaPhaDemoIds::USER_CUONG,
                'id_nhanh_cha' => GiaPhaDemoIds::NHANH_GOC,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::NHANH_BINH,
                'id_pha_he' => GiaPhaDemoIds::PHA_HE,
                'ten_nhanh' => 'Nhánh Nguyễn Văn Bình',
                'mo_ta' => 'Nhánh đời thứ hai do ông Nguyễn Văn Bình là người gốc nhánh.',
                'id_nguoi_goc' => GiaPhaDemoIds::TV_BINH,
                'id_truong_nhanh_hien_tai' => GiaPhaDemoIds::TV_HUNG,
                'id_nguoi_quan_ly' => GiaPhaDemoIds::USER_HUNG,
                'id_nhanh_cha' => GiaPhaDemoIds::NHANH_GOC,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'id_pha_he' => 2,
                'ten_nhanh' => 'Nhánh gốc họ Trần Văn',
                'mo_ta' => 'Nhánh khởi nguyên của gia phả họ Trần Văn.',
                'id_nguoi_goc' => 25,
                'id_truong_nhanh_hien_tai' => 29,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'id_pha_he' => 2,
                'ten_nhanh' => 'Nhánh Trần Văn Khải',
                'mo_ta' => 'Chi phái đời thứ hai do ông Trần Văn Khải làm người gốc nhánh.',
                'id_nguoi_goc' => 27,
                'id_truong_nhanh_hien_tai' => 31,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'id_pha_he' => 2,
                'ten_nhanh' => 'Nhánh Trần Văn Lộc',
                'mo_ta' => 'Chi phái đời thứ hai do ông Trần Văn Lộc làm người gốc nhánh.',
                'id_nguoi_goc' => 28,
                'id_truong_nhanh_hien_tai' => 34,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'id_pha_he' => 3,
                'ten_nhanh' => 'Nhánh gốc họ Lê Thị',
                'mo_ta' => 'Nhánh chính của gia phả họ Lê Thị.',
                'id_nguoi_goc' => 37,
                'id_truong_nhanh_hien_tai' => 42,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'id_pha_he' => 3,
                'ten_nhanh' => 'Nhánh Lê Thị Sen',
                'mo_ta' => 'Chi phái phát triển từ bà Lê Thị Sen.',
                'id_nguoi_goc' => 39,
                'id_truong_nhanh_hien_tai' => 44,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'id_pha_he' => 4,
                'ten_nhanh' => 'Nhánh gốc họ Phạm Đình',
                'mo_ta' => 'Nhánh chính của gia phả họ Phạm Đình.',
                'id_nguoi_goc' => 47,
                'id_truong_nhanh_hien_tai' => 52,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'id_pha_he' => 4,
                'ten_nhanh' => 'Nhánh Phạm Đình Sơn',
                'mo_ta' => 'Chi phái do ông Phạm Đình Sơn làm người gốc nhánh.',
                'id_nguoi_goc' => 49,
                'id_truong_nhanh_hien_tai' => 55,
                'id_nguoi_quan_ly' => null,
                'id_nhanh_cha' => 9,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

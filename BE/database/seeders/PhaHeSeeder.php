<?php

namespace Database\Seeders;

use App\Models\PhaHe;
use Illuminate\Database\Seeder;

class PhaHeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        PhaHe::insert([
            [
                'id' => GiaPhaDemoIds::PHA_HE,
                'ten_pha_he' => 'Gia phả họ Nguyễn Văn',
                'mo_ta' => 'Gia phả mẫu dùng để demo hệ thống quản lý cây gia phả tộc hệ.',
                'id_nguoi_sang_lap' => GiaPhaDemoIds::TV_TO,
                'doi_hien_tai' => 5,
                'trang_thai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'ten_pha_he' => 'Gia phả họ Trần Văn',
                'mo_ta' => 'Gia phả dòng họ Trần Văn với nhiều chi phái sinh sống tại miền Trung.',
                'id_nguoi_sang_lap' => 25,
                'doi_hien_tai' => 5,
                'trang_thai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'ten_pha_he' => 'Gia phả họ Lê Thị',
                'mo_ta' => 'Gia phả dòng họ Lê Thị, lưu giữ các nhánh gia đình phía Bắc.',
                'id_nguoi_sang_lap' => 37,
                'doi_hien_tai' => 4,
                'trang_thai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'ten_pha_he' => 'Gia phả họ Phạm Đình',
                'mo_ta' => 'Gia phả dòng họ Phạm Đình, có các chi nhánh tại Nam Bộ.',
                'id_nguoi_sang_lap' => 47,
                'doi_hien_tai' => 5,
                'trang_thai' => 'hoat_dong',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

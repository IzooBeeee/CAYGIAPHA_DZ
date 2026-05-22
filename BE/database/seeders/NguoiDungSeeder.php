<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        NguoiDung::insert([
            [
                'id' => GiaPhaDemoIds::USER_CUONG,
                'ho_ten' => 'Nguyễn Văn Cường',
                'email' => 'cuong@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'truong_nhanh',
                'trang_thai' => 'hoat_dong',
                'id_thanh_vien_gia_pha' => GiaPhaDemoIds::TV_CUONG,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::USER_HUNG,
                'ho_ten' => 'Nguyễn Văn Hùng',
                'email' => 'hung@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'truong_nhanh',
                'trang_thai' => 'hoat_dong',
                'id_thanh_vien_gia_pha' => GiaPhaDemoIds::TV_HUNG,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::USER_DUC,
                'ho_ten' => 'Nguyễn Văn Đức',
                'email' => 'duc@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'thanh_vien',
                'trang_thai' => 'hoat_dong',
                'id_thanh_vien_gia_pha' => GiaPhaDemoIds::TV_DUC,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::USER_NGOC,
                'ho_ten' => 'Nguyễn Thị Ngọc',
                'email' => 'ngoc@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'thanh_vien',
                'trang_thai' => 'hoat_dong',
                'id_thanh_vien_gia_pha' => GiaPhaDemoIds::TV_NGOC,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => GiaPhaDemoIds::USER_LOCKED,
                'ho_ten' => 'Tài Khoản Bị Khóa',
                'email' => 'locked@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'thanh_vien',
                'trang_thai' => 'bi_khoa',
                'id_thanh_vien_gia_pha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        NguoiDung::insert([
            [
                'id' => GiaPhaDemoIds::USER_ADMIN,
                'ho_ten' => 'Quản Trị Viên',
                'email' => 'admin@gmail.com',
                'mat_khau' => Hash::make('123456'),
                'vai_tro' => 'quan_tri_vien',
                'trang_thai' => 'hoat_dong',
                'id_thanh_vien_gia_pha' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

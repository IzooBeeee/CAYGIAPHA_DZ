<?php

namespace Database\Seeders;

use App\Models\CayGiaPhaChiaSe;
use Illuminate\Database\Seeder;

class CayGiaPhaChiaSeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        CayGiaPhaChiaSe::insert([
            [
                'id' => 1,
                'id_nguoi_tao' => GiaPhaDemoIds::USER_ADMIN,
                'id_nhanh_ho' => GiaPhaDemoIds::NHANH_GOC,
                'ma_chia_se' => 'nguyen-van-demo-public',
                'pham_vi' => 'cong_khai',
                'mat_khau' => null,
                'ngay_het_han' => now()->addYear(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

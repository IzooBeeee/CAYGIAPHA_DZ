<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuKienDongHo extends Model
{
    use HasFactory;

    protected $table = 'su_kien_dong_hos';

    protected $fillable = ['id_nhanh_ho', 'tieu_de', 'mo_ta', 'thoi_gian', 'dia_diem', 'loai_su_kien', 'id_nguoi_tao'];

    protected $casts = ['thoi_gian' => 'datetime'];

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_tao');
    }
}

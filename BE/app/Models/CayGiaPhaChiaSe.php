<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CayGiaPhaChiaSe extends Model
{
    use HasFactory;

    protected $table = 'cay_gia_pha_chia_ses';

    protected $fillable = ['id_nguoi_tao', 'id_nhanh_ho', 'ma_chia_se', 'pham_vi', 'mat_khau', 'ngay_het_han'];

    protected $hidden = ['mat_khau'];

    protected $casts = ['ngay_het_han' => 'datetime'];

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_tao');
    }

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }
}

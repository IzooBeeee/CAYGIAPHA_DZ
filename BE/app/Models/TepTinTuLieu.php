<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TepTinTuLieu extends Model
{
    use HasFactory;

    protected $table = 'tep_tin_tu_lieus';

    protected $fillable = ['id_thanh_vien_gia_pha', 'id_nhanh_ho', 'id_nguoi_tai_len', 'ten_tep', 'duong_dan_tep', 'loai_tep', 'mo_ta'];

    public function thanhVienGiaPha()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_thanh_vien_gia_pha');
    }

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }

    public function nguoiTaiLen()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_tai_len');
    }
}

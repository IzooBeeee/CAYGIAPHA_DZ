<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanhHo extends Model
{
    use HasFactory;

    protected $table = 'nhanh_hos';

    protected $fillable = [
        'id_pha_he',
        'ten_nhanh',
        'mo_ta',
        'id_nguoi_goc',
        'id_truong_nhanh_hien_tai',
        'id_nguoi_quan_ly',
        'id_nhanh_cha',
    ];

    public function phaHe()
    {
        return $this->belongsTo(PhaHe::class, 'id_pha_he');
    }

    public function nguoiGoc()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_nguoi_goc');
    }

    public function truongNhanhHienTai()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_truong_nhanh_hien_tai');
    }

    public function nguoiQuanLy()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_quan_ly');
    }

    public function nhanhCha()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_cha');
    }

    public function nhanhCon()
    {
        return $this->hasMany(NhanhHo::class, 'id_nhanh_cha');
    }

    public function thanhViens()
    {
        return $this->hasMany(ThanhVienGiaPha::class, 'id_nhanh_ho');
    }

    public function baiViets()
    {
        return $this->hasMany(BaiViet::class, 'id_nhanh_ho');
    }

    public function suKiens()
    {
        return $this->hasMany(SuKienDongHo::class, 'id_nhanh_ho');
    }
}

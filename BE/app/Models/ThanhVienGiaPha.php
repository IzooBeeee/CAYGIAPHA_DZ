<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThanhVienGiaPha extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'thanh_vien_gia_phas';

    protected $fillable = [
        'id_nhanh_ho',
        'ho_ten',
        'ten_khac',
        'gioi_tinh',
        'ngay_sinh',
        'ngay_mat',
        'con_song',
        'noi_sinh',
        'que_quan',
        'dia_chi_hien_tai',
        'so_dien_thoai',
        'anh_dai_dien',
        'doi_thu',
        'id_cha',
        'id_me',
        'tieu_su',
        'ghi_chu',
        'id_nguoi_tao',
        'id_nguoi_cap_nhat',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_mat' => 'date',
        'con_song' => 'boolean',
    ];

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }

    public function cha()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_cha');
    }

    public function me()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_me');
    }

    public function conTheoCha()
    {
        return $this->hasMany(ThanhVienGiaPha::class, 'id_cha');
    }

    public function conTheoMe()
    {
        return $this->hasMany(ThanhVienGiaPha::class, 'id_me');
    }

    public function honNhanChong()
    {
        return $this->hasMany(HonNhan::class, 'id_chong');
    }

    public function honNhanVo()
    {
        return $this->hasMany(HonNhan::class, 'id_vo');
    }

    public function moPhan()
    {
        return $this->hasOne(MoPhan::class, 'id_thanh_vien_gia_pha');
    }

    public function tepTinTuLieus()
    {
        return $this->hasMany(TepTinTuLieu::class, 'id_thanh_vien_gia_pha');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_tao');
    }

    public function nguoiCapNhat()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_cap_nhat');
    }
}

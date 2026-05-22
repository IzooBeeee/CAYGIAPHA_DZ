<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'nguoi_dungs';

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'vai_tro',
        'trang_thai',
        'id_thanh_vien_gia_pha',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'mat_khau' => 'hashed',
    ];

    public function getAuthPassword(): string
    {
        return $this->mat_khau;
    }

    public function thanhVienGiaPha()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_thanh_vien_gia_pha');
    }

    public function nhanhHosQuanLy()
    {
        return $this->belongsToMany(NhanhHo::class, 'nhanh_hos', 'id_nguoi_quan_ly', 'id_nhanh_ho');
    }

    public function yeuCauDaGui()
    {
        return $this->hasMany(YeuCauChinhSua::class, 'id_nguoi_gui');
    }

    public function yeuCauDaDuyet()
    {
        return $this->hasMany(YeuCauChinhSua::class, 'id_nguoi_duyet');
    }

    public function baiViets()
    {
        return $this->hasMany(BaiViet::class, 'id_nguoi_dung');
    }

    public function thongBaoNhan()
    {
        return $this->hasMany(ThongBao::class, 'id_nguoi_nhan');
    }

    public function laQuanTriVien(): bool
    {
        return $this->vai_tro === 'quan_tri_vien';
    }

    public function laTruongNhanh(): bool
    {
        return $this->vai_tro === 'truong_nhanh';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = "nguoi_dungs";
    protected $fillable = ["ho_ten", "email", "mat_khau", "so_dien_thoai", "avatar", "vai_tro", "trang_thai", "hash_reset"];
    protected $hidden = ["mat_khau", "remember_token"];
    public function getAuthPassword() { return $this->mat_khau; }
    
    public function nhatKyHoatDongs() { return $this->hasMany(NhatKyHoatDong::class); }
    public function dongGops() { return $this->hasMany(DongGop::class); }

}

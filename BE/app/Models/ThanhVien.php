<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThanhVien extends Model
{
    use HasFactory;

    protected $table = "thanh_viens";
    protected $fillable = [
        "ho_ten", 
        "ten_goi", 
        "gioi_tinh", 
        "ngay_sinh", 
        "ngay_mat", 
        "noi_sinh", 
        "nghe_nghiep", 
        "avatar", 
        "doi_thu", 
        "chi_nhanh_id", 
        "cha_id", 
        "me_id", 
        "ghi_chu", 
        "trang_thai",
        "loai_quan_he",
        "spouse_of_id"
    ];
    
    public function chiNhanh() { return $this->belongsTo(ChiNhanh::class); }
    public function cha() { return $this->belongsTo(ThanhVien::class, "cha_id"); }
    public function me() { return $this->belongsTo(ThanhVien::class, "me_id"); }
    public function conCai() { return $this->hasMany(ThanhVien::class, "cha_id")->orWhere("me_id", $this->id); }
    public function hinhAnhs() { return $this->hasMany(HinhAnh::class); }
    public function moPhans() { return $this->hasMany(MoPhan::class); }
    public function voChongs() { return $this->hasMany(VoChong::class, "chong_id")->orWhere("vo_id", $this->id); }
    public function conNuois() { return $this->hasMany(ConNuoi::class, "cha_me_id"); }
    public function thamGiaSuKiens() { return $this->hasMany(ThamGiaSuKien::class); }

}

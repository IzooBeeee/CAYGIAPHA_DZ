<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NhatKyHoatDong extends Model
{
    use HasFactory;

    protected $table = "nhat_ky_hoat_dongs";
    protected $fillable = ["nguoi_dung_id", "hanh_dong", "thoi_gian"];
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class); }

}

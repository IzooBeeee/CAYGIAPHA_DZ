<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoPhan extends Model
{
    use HasFactory;

    protected $table = "mo_phans";
    protected $fillable = ["thanh_vien_id", "ten_mo", "dia_chi", "kinh_do", "vi_do", "hinh_anh", "ghi_chu"];
    public function thanhVien() { return $this->belongsTo(ThanhVien::class); }

}

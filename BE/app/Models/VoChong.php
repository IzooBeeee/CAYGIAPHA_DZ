<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoChong extends Model
{
    use HasFactory;

    protected $table = "vo_chongs";
    protected $fillable = ["chong_id", "vo_id", "ngay_cuoi", "trang_thai"];
    public function chong() { return $this->belongsTo(ThanhVien::class, "chong_id"); }
    public function vo() { return $this->belongsTo(ThanhVien::class, "vo_id"); }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HinhAnh extends Model
{
    use HasFactory;

    protected $table = "hinh_anhs";
    protected $fillable = ["thanh_vien_id", "duong_dan", "mo_ta"];
    public function thanhVien() { return $this->belongsTo(ThanhVien::class); }

}

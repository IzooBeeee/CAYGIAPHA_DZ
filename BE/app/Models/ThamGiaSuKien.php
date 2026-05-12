<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThamGiaSuKien extends Model
{
    use HasFactory;

    protected $table = "tham_gia_su_kiens";
    protected $fillable = ["su_kien_id", "thanh_vien_id"];
    public function suKien() { return $this->belongsTo(SuKien::class); }
    public function thanhVien() { return $this->belongsTo(ThanhVien::class); }

}

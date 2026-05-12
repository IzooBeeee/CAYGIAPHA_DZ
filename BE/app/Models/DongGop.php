<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DongGop extends Model
{
    use HasFactory;

    protected $table = "dong_gops";
    protected $fillable = ["nguoi_dung_id", "noi_dung", "trang_thai"];
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class); }

}

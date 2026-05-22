<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'bai_viets';

    protected $fillable = ['id_nguoi_dung', 'id_nhanh_ho', 'tieu_de', 'duong_dan', 'noi_dung', 'anh_dai_dien', 'trang_thai'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }

    public function binhLuans()
    {
        return $this->hasMany(BinhLuan::class, 'id_bai_viet');
    }
}

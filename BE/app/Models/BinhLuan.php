<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luans';

    protected $fillable = ['id_nguoi_dung', 'id_bai_viet', 'noi_dung', 'trang_thai'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function baiViet()
    {
        return $this->belongsTo(BaiViet::class, 'id_bai_viet');
    }
}

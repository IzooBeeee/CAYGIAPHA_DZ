<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'thong_baos';

    protected $fillable = ['id_nguoi_gui', 'id_nguoi_nhan', 'id_nhanh_ho', 'tieu_de', 'noi_dung', 'loai_thong_bao', 'da_doc'];

    protected $casts = ['da_doc' => 'boolean'];

    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_gui');
    }

    public function nguoiNhan()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_nhan');
    }

    public function nhanhHo()
    {
        return $this->belongsTo(NhanhHo::class, 'id_nhanh_ho');
    }
}

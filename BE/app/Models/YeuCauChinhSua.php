<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuCauChinhSua extends Model
{
    use HasFactory;

    protected $table = 'yeu_cau_chinh_suas';

    protected $fillable = [
        'id_nguoi_gui',
        'id_thanh_vien_gia_pha',
        'loai_yeu_cau',
        'du_lieu_cu',
        'du_lieu_moi',
        'trang_thai',
        'id_nguoi_duyet',
        'thoi_gian_duyet',
        'ly_do',
    ];

    protected $casts = [
        'du_lieu_cu' => 'array',
        'du_lieu_moi' => 'array',
        'thoi_gian_duyet' => 'datetime',
    ];

    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_gui');
    }

    public function thanhVienGiaPha()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_thanh_vien_gia_pha');
    }

    public function nguoiDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_duyet');
    }
}

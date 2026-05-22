<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKyHoatDong extends Model
{
    use HasFactory;

    protected $table = 'nhat_ky_hoat_dongs';

    protected $fillable = ['id_nguoi_dung', 'hanh_dong', 'ten_bang', 'id_ban_ghi', 'du_lieu_cu', 'du_lieu_moi', 'dia_chi_ip', 'trinh_duyet'];

    protected $casts = [
        'du_lieu_cu' => 'array',
        'du_lieu_moi' => 'array',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }
}

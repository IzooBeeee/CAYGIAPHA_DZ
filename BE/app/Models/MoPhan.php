<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoPhan extends Model
{
    use HasFactory;

    protected $table = 'mo_phans';

    protected $fillable = ['id_thanh_vien_gia_pha', 'dia_chi_mo', 'toa_do_lat', 'toa_do_lng', 'ngay_an_tang', 'hinh_anh', 'ghi_chu'];

    protected $casts = [
        'ngay_an_tang' => 'date',
        'toa_do_lat' => 'decimal:7',
        'toa_do_lng' => 'decimal:7',
    ];

    public function thanhVienGiaPha()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_thanh_vien_gia_pha');
    }
}

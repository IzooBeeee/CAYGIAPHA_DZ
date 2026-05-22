<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaHe extends Model
{
    use HasFactory;

    protected $table = 'pha_hes';

    protected $fillable = [
        'ten_pha_he',
        'mo_ta',
        'id_nguoi_sang_lap',
        'doi_hien_tai',
        'trang_thai',
    ];

    public function nguoiSangLap()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_nguoi_sang_lap');
    }

    public function nhanhHos()
    {
        return $this->hasMany(NhanhHo::class, 'id_pha_he');
    }

    public function lichSuPhaHes()
    {
        return $this->hasMany(LichSuPhaHe::class, 'id_pha_he');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonNhan extends Model
{
    use HasFactory;

    protected $table = 'hon_nhans';

    protected $fillable = [
        'id_chong',
        'id_vo',
        'ngay_ket_hon',
        'ngay_ly_hon',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_ket_hon' => 'date',
        'ngay_ly_hon' => 'date',
    ];

    public function chong()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_chong');
    }

    public function vo()
    {
        return $this->belongsTo(ThanhVienGiaPha::class, 'id_vo');
    }
}

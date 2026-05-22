<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuPhaHe extends Model
{
    use HasFactory;

    protected $table = 'lich_su_pha_hes';

    protected $fillable = ['id_pha_he', 'tieu_de', 'noi_dung', 'moc_thoi_gian'];

    protected $casts = ['moc_thoi_gian' => 'date'];

    public function phaHe()
    {
        return $this->belongsTo(PhaHe::class, 'id_pha_he');
    }
}

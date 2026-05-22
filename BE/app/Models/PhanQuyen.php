<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhanQuyen extends Model
{
    use HasFactory;

    protected $table = 'phan_quyens';

    protected $fillable = [
        'id_chuc_vu',
        'id_chuc_nang',
        'co_quyen',
    ];

    protected $casts = [
        'co_quyen' => 'boolean',
    ];

    public function chucVu(): BelongsTo
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu');
    }

    public function chucNang(): BelongsTo
    {
        return $this->belongsTo(ChucNang::class, 'id_chuc_nang');
    }
}

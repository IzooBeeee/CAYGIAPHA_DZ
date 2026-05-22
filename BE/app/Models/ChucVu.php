<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChucVu extends Model
{
    use HasFactory;

    protected $table = 'chuc_vus';

    protected $fillable = [
        'ten_chuc_vu',
        'slug_chuc_vu',
        'tinh_trang',
    ];

    protected $casts = [
        'tinh_trang' => 'integer',
    ];

    public function nhanViens(): HasMany
    {
        return $this->hasMany(NhanVien::class, 'id_chuc_vu');
    }

    public function phanQuyens(): HasMany
    {
        return $this->hasMany(PhanQuyen::class, 'id_chuc_vu');
    }

    public function hasPermission(string $slugChucNang): bool
    {
        return $this->phanQuyens()
            ->whereHas('chucNang', function ($query) use ($slugChucNang) {
                $query->where('slug_chuc_nang', $slugChucNang);
            })
            ->where('co_quyen', true)
            ->exists();
    }
}

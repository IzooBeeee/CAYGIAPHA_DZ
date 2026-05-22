<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class NhanVien extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'nhan_viens';

    protected $fillable = [
        'email',
        'ho_va_ten',
        'mat_khau',
        'so_dien_thoai',
        'dia_chi',
        'ngay_sinh',
        'avatar',
        'tinh_trang',
        'id_chuc_vu',
        'is_master',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected $casts = [
        'mat_khau' => 'hashed',
        'tinh_trang' => 'integer',
        'is_master' => 'boolean',
        'ngay_sinh' => 'date',
    ];

    public function getAuthPassword(): string
    {
        return $this->mat_khau;
    }

    public function chucVu(): BelongsTo
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu');
    }

    public function isMaster(): bool
    {
        return $this->is_master === true;
    }

    public function isActive(): bool
    {
        return $this->tinh_trang === 1;
    }

    public function hasPermission(string $slugChucNang): bool
    {
        if ($this->isMaster()) {
            return true;
        }

        return $this->chucVu && $this->chucVu->hasPermission($slugChucNang);
    }
}

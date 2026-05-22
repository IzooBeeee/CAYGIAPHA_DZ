<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChucNang extends Model
{
    use HasFactory;

    protected $table = 'chuc_nangs';

    protected $fillable = [
        'ten_chuc_nang',
        'slug_chuc_nang',
        'mo_ta',
    ];

    public function phanQuyens(): HasMany
    {
        return $this->hasMany(PhanQuyen::class, 'id_chuc_nang');
    }
}

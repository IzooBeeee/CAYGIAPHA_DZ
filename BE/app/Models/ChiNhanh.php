<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChiNhanh extends Model
{
    use HasFactory;

    protected $table = "chi_nhanhs";
    protected $fillable = ["ten_chi", "mo_ta"];
    public function thanhViens() { return $this->hasMany(ThanhVien::class); }

}

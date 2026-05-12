<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NhaThoHo extends Model
{
    use HasFactory;

    protected $table = "nha_tho_hos";
    protected $fillable = ["ten_nha_tho", "dia_chi", "hinh_anh", "mo_ta"];

}

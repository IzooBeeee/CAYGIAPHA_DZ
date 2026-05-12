<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = "thong_baos";
    protected $fillable = ["tieu_de", "noi_dung"];

}

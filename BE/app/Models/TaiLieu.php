<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaiLieu extends Model
{
    use HasFactory;

    protected $table = "tai_lieus";
    protected $fillable = ["tieu_de", "file_path", "mo_ta"];

}

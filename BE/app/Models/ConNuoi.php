<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConNuoi extends Model
{
    use HasFactory;

    protected $table = "con_nuois";
    protected $fillable = ["cha_me_id", "con_id", "ghi_chu"];
    public function chaMe() { return $this->belongsTo(ThanhVien::class, "cha_me_id"); }
    public function con() { return $this->belongsTo(ThanhVien::class, "con_id"); }

}

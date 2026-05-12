<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'husband_id',
        'wife_id',
        'married_date',
        'divorced_date',
        'status',
    ];

    protected $casts = [
        'married_date' => 'date',
        'divorced_date' => 'date',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function husband()
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }
}

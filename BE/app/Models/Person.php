<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'full_name',
        'gender',
        'birth_date',
        'death_date',
        'birth_place',
        'avatar',
        'biography',
        'father_id',
        'mother_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function father()
    {
        return $this->belongsTo(Person::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function childrenAsFather()
    {
        return $this->hasMany(Person::class, 'father_id');
    }

    public function childrenAsMother()
    {
        return $this->hasMany(Person::class, 'mother_id');
    }

    public function children()
    {
        return $this->childrenAsFather->merge($this->childrenAsMother);
    }

    public function marriagesAsHusband()
    {
        return $this->hasMany(Marriage::class, 'husband_id');
    }

    public function marriagesAsWife()
    {
        return $this->hasMany(Marriage::class, 'wife_id');
    }

    public function spouses()
    {
        $husbands = $this->marriagesAsWife->map->husband;
        $wives = $this->marriagesAsHusband->map->wife;
        return $husbands->merge($wives)->filter();
    }
}

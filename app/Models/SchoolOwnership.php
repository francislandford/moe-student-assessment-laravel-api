<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolOwnership extends Model
{
    use HasFactory;

    protected $table = 'ownership';
    protected $fillable = [
        'name',
    ];

    // Optional: relationship to schools
    public function schools()
    {
        return $this->hasMany(School::class, 'school_ownership', 'name');
        // If you switch to ID-based foreign key later: hasMany(School::class);
    }
}

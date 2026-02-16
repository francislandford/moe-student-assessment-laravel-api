<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolType extends Model
{
    use HasFactory;

    protected $table = 'school_type';
    protected $fillable = [
        'name',
    ];

    // Optional: relationship back to schools
    public function schools()
    {
        return $this->hasMany(School::class, 'school_type', 'name');
        // or if using ID: return $this->hasMany(School::class);
    }
}

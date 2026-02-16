<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolLevel extends Model
{
    use HasFactory;

    protected $table = 'level';
    protected $fillable = [
        'code',
        'name',
    ];

    // Optional: relationship to schools (if using string code reference)
    public function schools()
    {
        return $this->hasMany(School::class, 'school_level', 'code');
        // Alternative: if you later switch to ID foreign key → hasMany(School::class);
    }
}

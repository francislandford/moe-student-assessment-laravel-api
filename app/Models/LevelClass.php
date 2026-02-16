<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelClass extends Model
{
    use HasFactory;

    protected $table = 'level_classes';

    protected $fillable = ['level', 'name'];

    // Optional: relationship to SchoolLevel if you have one
}

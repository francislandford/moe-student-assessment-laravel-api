<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParticipation extends Model
{
    use HasFactory;

    protected $table = 'students';
    public $timestamps = false;

    protected $fillable = [
        'school',           // school code (unique identifier per school)
        'question',         // e.g. "Q1", "Q2"
        'score',            // 0 or 1 (No / Yes)
        'date',             // when submitted/updated
    ];

    protected $casts = [
        'score' => 'integer',
        'date'  => 'datetime',
    ];
}

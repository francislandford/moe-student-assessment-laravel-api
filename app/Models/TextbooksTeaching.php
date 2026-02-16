<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextbooksTeaching extends Model
{
    use HasFactory;

    protected $table = 'textbooks';
    public $timestamps = false;

    protected $fillable = [
        'school',           // school code (unique per school)
        'question',         // e.g. "Q1", "Q2"
        'score',            // 0 or 1 (No / Yes)
        'date',             // submission/update date
    ];

    protected $casts = [
        'score' => 'integer',
        'date'  => 'datetime',
    ];
}

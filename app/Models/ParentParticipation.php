<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentParticipation extends Model
{
    use HasFactory;

    protected $table = 'parents';
    public $timestamps = false;

    protected $fillable = [
        'school',           // school code (unique per school)
        'question',         // question identifier (e.g. "Q1", "Q2")
        'score',            // 0 or 1
        'date',             // submission/update date
    ];

    protected $casts = [
        'score' => 'integer',
        'date'  => 'datetime',
    ];
}

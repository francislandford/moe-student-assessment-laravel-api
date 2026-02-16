<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'school',      // school_code
        'question',    // question ID or code
        'score',       // 0 = No, 1 = Yes
        'date',
    ];

    protected $casts = [
        'date'   => 'datetime',
        'score'  => 'integer',
    ];

    // Optional relationship to School
    public function school()
    {
        return $this->belongsTo(School::class, 'school', 'school_code');
    }
}

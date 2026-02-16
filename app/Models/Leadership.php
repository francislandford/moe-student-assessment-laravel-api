<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'leadership';

    protected $fillable = [
        'school',      // school_code
        'question',    // question ID (string or int)
        'score',       // 0 or 1 (or 2 for double-point questions)
        'date',
    ];

    protected $casts = [
        'date'   => 'datetime',
        'score'  => 'integer',
    ];

    // Relationship to School (optional)
    public function school()
    {
        return $this->belongsTo(School::class, 'school', 'school_code');
    }
}

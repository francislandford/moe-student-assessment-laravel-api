<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCheck extends Model
{
    use HasFactory;

    protected $table = 'doc_check';
    public $timestamps = false;

    protected $fillable = [
        'school',      // school_code
        'question',    // question ID or code
        'score',       // 0, 1, or 2
        'date',
    ];

    protected $casts = [
        'date'   => 'datetime',
        'score'  => 'integer',
    ];

    // Optional: relationship to School
    public function school()
    {
        return $this->belongsTo(School::class, 'school', 'school_code');
    }
}

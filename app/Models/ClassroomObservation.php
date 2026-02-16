<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomObservation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'classroom';

    protected $fillable = [
        'school',          // school_code
        'class_num',       // 1, 2, or 3 (for the three observed classrooms)
        'grade',           // e.g. "Grade 7"
        'subject',         // e.g. "Mathematics"
        'teacher',         // teacher's name
        'question',        // question ID/code
        'score',           // 0 = No, 1 = Yes
        'date',
        'nb_male',
        'nb_female'
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

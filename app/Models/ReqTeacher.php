<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReqTeacher extends Model
{
    use HasFactory;

    protected $table = 'req_teachers';
    public $timestamps = false;

    protected $fillable = [
        'school',
        'level',
        'self_contain',
        'ass_teacher',
        'volunteers',
        'students',
        'num_req',
        'date',
    ];

    protected $casts = [
        'date'        => 'date:Y-m-d',
        'ass_teacher' => 'integer',
        'volunteers'  => 'integer',
        'students'    => 'integer',
        'num_req'     => 'integer',
    ];
}

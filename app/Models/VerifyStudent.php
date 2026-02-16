<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyStudent extends Model
{
    use HasFactory;

    protected $table = 'verify_students';
    public $timestamps = false;

    protected $fillable = [
        'school',
        'classes',
        'emis_male',
        'count_male',
        'emis_female',
        'count_female',
        'date',
    ];

    protected $casts = [
        'date'         => 'date:Y-m-d',
        'emis_male'    => 'integer',
        'count_male'   => 'integer',
        'emis_female'  => 'integer',
        'count_female' => 'integer',
    ];
}

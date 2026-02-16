<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    public $timestamps = false;

    protected $fillable = [
        'school',
        'fname',
        'gender',
        'position',
        'week_load',
        'present',
        'bio_id',
        'pay_id',
        'qualification',
        'date',
    ];

    protected $casts = [
        'date'       => 'date:Y-m-d',
        'week_load'  => 'integer',
    ];
}

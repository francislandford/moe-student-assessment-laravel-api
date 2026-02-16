<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absent extends Model
{
    use HasFactory;

    protected $table = 'absents';
    public $timestamps=false;// important if table name ≠ model name plural

    protected $fillable = [
        'school',
        'fname',
        'bio_id',
        'pay_id',
        'reason',
        'excuse',
        'date',
    ];

    // If you want date to be treated as Carbon/date instance
    protected $dates = [
        'date',
    ];

    // Optional: cast date field (Laravel 9+ / 10+ / 11+ style)
    protected $casts = [
        'date' => 'date:Y-m-d',   // or 'datetime' if you need time too
    ];
}

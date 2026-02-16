<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePaid extends Model
{
    use HasFactory;

    protected $table = 'fees_paid';
    public $timestamps = false;

    protected $fillable = [
        'school',
        'fee',
        'pay',
        'purpose',
        'amount',
        'date',
    ];

    protected $casts = [
        'amount' => 'float',
        'date'   => 'date:Y-m-d',
    ];
}

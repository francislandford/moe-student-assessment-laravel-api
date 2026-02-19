<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    public $timestamps = false;
    protected $fillable = [
        'county',
        'district',
        'school_level',
        'compliance',
        'emis_code',
        'school_type',
        'nb_room',
        'school_ownership',
        'community',
        'school_code',
        'school_name',
        'tvet',
        'accelerated',
        'alternative',
        'year_establish',
        'permit',
        'permit_num',
        'principal_name',
        'school_contact',
        'email',
        'latitude',
        'longitude',
        'all_teacher_present',
        'verify_comment',
        'charge_fees',
        'collector'
    ];

    protected $casts = [
        'tvet'        => 'boolean',
        'accelerated' => 'boolean',
        'alternative' => 'boolean',
        'year_establish' => 'integer',
    ];

    // Optional: accessor for display
    public function getFullLocationAttribute(): string
    {
        return trim("{$this->county} - {$this->district}, {$this->community}");
    }
}

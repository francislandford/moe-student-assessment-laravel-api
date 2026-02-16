<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';

    protected $fillable = [
        'county',
        'd_name',
        'date',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];

    // Optional: relationship to schools (string-based)
    public function schools()
    {
        return $this->hasMany(School::class, 'district', 'd_name')
            ->where('county', $this->county);
    }

    // Optional: scope for filtering by county
    public function scopeByCounty($query, string $county)
    {
        return $query->where('county', $county);
    }
}

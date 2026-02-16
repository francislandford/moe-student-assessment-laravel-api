<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_count';   // important – matches your column name

    protected $table = 'counties';

    public $incrementing = true;

    protected $fillable = [
        'county',
    ];

    // Optional: relationship to schools
    public function schools()
    {
        return $this->hasMany(School::class, 'county', 'county');
    }
}

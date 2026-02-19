<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get all positions formatted for dropdown
     */
    public static function getForDropdown()
    {
        return self::orderBy('name')->get(['id', 'name']);
    }
}

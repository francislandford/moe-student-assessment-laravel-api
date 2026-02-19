<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['fee'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get all fees formatted for dropdown
     */
    public static function getForDropdown()
    {
        return self::orderBy('name')->get(['id', 'fee']);
    }
}

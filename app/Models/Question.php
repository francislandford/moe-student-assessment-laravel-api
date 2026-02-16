<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat',
        'name',
    ];

    // Scope for filtering by category
    public function scopeByCategory($query, string $category)
    {
        return $query->where('cat', $category);
    }

    // Default sorting
    protected static function booted()
    {
        static::addGlobalScope('id', function ($query) {
            $query->orderBy('id','DESC');
        });
    }
}

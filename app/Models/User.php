<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'username',
        'password',
        'usertype',
        'phone',
        'project',
        'cat',
        'district',
        'photo',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'project'           => 'integer',
    ];

    // Optional: accessor for photo URL (if stored as filename)
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        // Adjust based on your storage setup (public disk, S3, etc.)
        return asset('storage/photos/' . $this->photo);
        // or: return Storage::url('photos/' . $this->photo);
    }
}

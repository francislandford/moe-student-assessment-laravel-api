<?php
// app/Models/PasswordReset.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'email'; // or whatever your primary key is
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'email', 'token', 'created_at'
    ];
}

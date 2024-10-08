<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tokens extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_type',
        'access_token',
        'refresh_token',
        'expires_in'
    ];
}

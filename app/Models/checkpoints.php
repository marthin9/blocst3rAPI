<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkpoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkpoint_id',
        'name',
        'chief',
        'chief_phone',
        'station',
        'division',
        'bureau',
        'type',
        'start_at',
        'end_at',
        'address',
        'is_active',
        'approval',
    ];
}

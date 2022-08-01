<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Vote extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'provider_id',
        'poll_id',
        'value'
    ];

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMinutes(15));
    }
}

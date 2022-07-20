<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class QueuedPoll extends Model
{
    use HasFactory, Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'options',
        'length',
        'delay',
        'validated',
        'provider_id',
        'created_by_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'created_by_id' => 'integer'
    ];

    /**
     * Get the user that created the poll
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by_id');
    }

    /**
     * Get the stream user the poll belongs to
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function streamUser()
    {
        return $this->belongsTo(User::class, 'provider_id', 'provider_id');
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subHour(16));
    }

    /**
     * Get a human readable version of the created_at attribute
     */
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
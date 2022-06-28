<?php

namespace App\Models;

use App\Collections\ActiveIncidentDefs;
use App\Collections\AvailableIncidentDefsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentDef extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'def_name',
        'mod_id',
        'label',
        'letter_label',
        'letter_text',
        'enabled',
        'is_active',
    ];

    /**
     * Create a new Eloquent Collection instance
     * 
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeIncidentDefs(array $models = [])
    {
        return new ActiveIncidentDefs($models);
    }
}

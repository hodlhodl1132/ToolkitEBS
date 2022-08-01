<?php

namespace App\Models;

use App\Collections\ActiveIncidentDefs;
use App\Collections\AvailableIncidentDefsCollection;
use App\Collections\EnabledIncidentDefs;
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
        'description',
        'enabled',
        'is_active',
    ];

    /**
     * Create a new Eloquent Collection instance where the models are active
     * 
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeIncidentDefs(array $models = [])
    {
        return new ActiveIncidentDefs($models);
    }

    /**
     * Create a new Eloquent Collection instance where the models are enabled
     * 
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function enabledIncidentDefs(array $models = [])
    {
        return new EnabledIncidentDefs($models);
    }
}

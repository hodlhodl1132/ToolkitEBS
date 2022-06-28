<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ActiveIncidentDefs extends Collection
{
    public function availableIncidentDefs(array $models = [])
    {
        return $this->filter(function ($model) {
            return $model->enabled && $model->is_active;
        });
    }
}
<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class EnabledIncidentDefs extends Collection
{
    public function enabledIncidentDefs(array $models = [])
    {
        return $this->filter(function ($model) {
            return $model->enabled;
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\IncidentDef;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class IncidentDefController extends Controller
{
    /**
     * Update the resources
     * 
     * @param Request $request
     */
    public function update(Request $request)
    {
        $validated = [];
        try {
            $validated = $this->validate($request, [
                '*.def_name' => 'required|string|max:255',
                '*.mod_id' => 'required_with:*.def_name|string|max:255',
                '*.label' => 'required_with:*.def_name|string|max:255',
                '*.letter_label' => 'required_with:*.def_name|string|max:255',
                '*.letter_text' => 'required_with:*.def_name|string|max:255'
            ]);
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        /**
         * @var User $user
         */
         $user = $request->user();

         /**
          * Prevent users from updating incident defs that are not part of their mod pack
          */
        if (count($validated) > 250) {
            return response()->json(['error' => 'Too many incident definitions'], 400);
        }

        /**
         * @var \Illuminate\Database\Eloquent\Collection $currentIncidentDefs
         */
         $currentIncidentDefs = $user->incidentDefs;

        /**
         * Prevent users from uploading more than 500 incident definitions
         */
         if (count($validated) + count($currentIncidentDefs) > 500) {
             return response()->json(['error' => 'Too many incident definitions'], 400);
         }

         if (count($currentIncidentDefs))
            $currentIncidentDefs->toQuery()->update(['is_active' => false]);

         foreach ($validated as $incidentDef) {
            $def = $currentIncidentDefs->filter(function ($item) use ($incidentDef) {
                return ($item->def_name === $incidentDef['def_name'] &&
                        $item->mod_id === $incidentDef['mod_id']);
            })->first();

            if ($def !== null) {
                $def->is_active = true;
                $def->label = $incidentDef['label'];
                $def->letter_label = $incidentDef['letter_label'];
                $def->letter_text = $incidentDef['letter_text'];
            } else {
                $currentIncidentDefs->add(new IncidentDef([
                    'def_name' => $incidentDef['def_name'],
                    'mod_id' => $incidentDef['mod_id'],
                    'label' => $incidentDef['label'],
                    'letter_label' => $incidentDef['letter_label'],
                    'letter_text' => $incidentDef['letter_text'],
                    'enabled' => true,
                    'is_active' => true,
                ]));
            }
         }

         $user->incidentDefs()->saveMany($currentIncidentDefs);
        
        return response()->json(['success' => 'Incident Defs updated'], 200);
    }
}

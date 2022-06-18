<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Store the resource
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        /**
         * @var Illuminate\Session\Store $session
         */
        $session = $request->session();

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            $session->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        /**
         * @var \App\Models\User $targetedUser
         */
        $targetedUser = User::find($validated['user_id']);
        /**
         * @var Permission
         */
        $permission = Permission::find($validated['id']);
        $permissionName = $permission->name;

        if (!$user->hasPermissionTo('admin.users.edit'))
        {
            return response('', 403);
        }

        if ($permission == null || $targetedUser == null)
        {
            $session->flash('errors', 'There has been a critical error.');
        }

        $targetedUser->givePermissionTo($permission);

        return response()
            ->redirectToRoute('admin.users.show', [
                'id' => $targetedUser->id
            ]);
    }

    /**
     * Delete the resource
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function destroy(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();

        /**
         * @var Illuminate\Session\Store $session
         */
        $session = $request->session();

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            $session->flash('errors', $validator->errors());
        }

        $validated = $validator->validated();

        /**
         * @var \App\Models\User $targetedUser
         */
        $targetedUser = User::find($validated['user_id']);
        /**
         * @var Permission
         */
        $permission = Permission::find($validated['id']);
        $permissionName = $permission->name;

        if ($permission == null || $targetedUser == null) {
            $session->flash('errors', 'There has been a critical error.');
        }

        if (!$user->hasPermissionTo('admin.users.edit')) {
            return response('', 403);
        }

        if ($targetedUser->hasPermissionTo($permission))
        {
            $targetedUser->revokePermissionTo($permission);
        }

        return response()
            ->redirectToRoute('admin.users.show', [
                'id' => $targetedUser->id
            ]);
    }
}

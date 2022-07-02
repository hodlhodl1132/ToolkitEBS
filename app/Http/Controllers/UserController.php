<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request)
    {
        return view('users.index', [
            'users' => User::paginate(10)
        ]);
    }

    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'required|string|min:3|max:30'
            ]);

            $users = User::search($validated['search'])
                ->paginate(10);

            return view('users.index', [
                'users' => $users
            ]);
            
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return redirect()
                ->route('admin.users.index')
                ->withErrors($e->getMessage());
        }
    }

    /**
     * Display the resource
     */
    public function show(int $id)
    {
        /**
         * @var \App\Models\User
         */
        $user = User::find($id);

        if ($user == null) {
            return response()->redirectToRoute('admin.users.index');
        }
        
        $allPermissions = Permission::all();
        $userPermissions = $user->getAllPermissions();
        $permissions = [];

        foreach ($allPermissions as $permission) {
            if ($userPermissions->find($permission->id) == null)
            {
                array_push($permissions, $permission);
            }
        }

        return view('users.show', [
            'user' => $user,
            'permissions' => $permissions
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $users = User::paginate(10);

        return view('users.index', [
            'users' => $users
        ]);
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
        
        $allPermissions = Permission::all();
        $userPermissions = $user->getAllPermissions();
        $permissions = [];

        foreach ($allPermissions as $permission) {
            if ($userPermissions->find($permission->id) == null)
            {
                array_push($permissions, $permission);
            }
        }


        if ($user == null)
        {
            return response()->redirectToRoute('admin.users.index');
        }

        return view('users.show', [
            'user' => $user,
            'permissions' => $permissions
        ]);
    }
}

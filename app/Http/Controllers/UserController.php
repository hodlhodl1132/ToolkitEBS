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
        if ($request->method == "POST")
        {

        } else {
            $users = User::paginate(10);  
        }

        return view('users.index', [
            'users' => $users
        ]);
    }

    public function search(Request $request)
    {
        $users = [];
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'required|string|min:3|max:30'
            ]);
            $validator->validate();
            $validated = $validator->validated();
            $users = User::search($validated['search'])
                ->paginate(10);
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        /**
         * @var Illuminate\Session\Store $session
         */
        $session = $request->session();
        $session->flash('errors', $validator->errors());

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

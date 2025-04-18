<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (isset($data['search'])) {
            $users = User::where('name', 'like', '%' . $data['search'] . '%')
                ->orWhere('email', 'like', '%' . $data['search'] . '%')
                ->orWhere('email', 'like', '%' . $data['search'] . '%')
                ->get();
        } else {
            $users = User::all();
        }
        return view('admin.users.index', compact('users', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole($request->role);
        $user = new UserResource($user);
        // return response()->json($user);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = new UserResource($user);
        // return view('admin.users.show', compact('user'));
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user = new UserResource($user);
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
            unset($data['password_confirmation']);
        }
        $user->update($data);
        $user->syncRoles($data['role']);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}

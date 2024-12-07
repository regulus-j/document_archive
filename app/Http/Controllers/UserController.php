<?php

namespace App\Http\Controllers;

use App\Mail\UserInvite;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $users = User::latest()->paginate(perPage: 5);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Search for users based on name, email, and role.
     */
    public function search(Request $request): View
    {
        $query = User::query();
        $roles = Role::all();

        if ($request->filled('name')) {
            $query->where('first_name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%'.$request->email.'%');
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->role.'%');
            });
        }

        $users = $query->paginate(5);

        return view('users.index', compact('users', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        $offices = Office::pluck('name', 'id')->all();

        return view('users.create', compact('roles', 'offices'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $temp_pass = Str::random(12);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'offices' => 'required|array', // Changed from 'office'
            'offices.*' => 'exists:offices,id', // Validate each office ID
            'roles' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($temp_pass),
        ]);

        // Attach multiple offices
        $user->offices()->attach($request->offices);

        $user->assignRole($request->input('roles'));

        $roleNames = $user->roles->pluck('name')->implode(', ');

        Mail::to($user->email)->send(new UserInvite(
            $user->first_name,
            $user->email,
            $temp_pass,
            $roleNames,
            route('login')
        ));

        $temp_pass = null;

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        $offices = Office::pluck('name', 'id')->all();
        $userOffices = $user->offices->pluck('id')->all();

        return view('users.edit', compact('user', 'roles', 'userRoles', 'offices', 'userOffices'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'offices' => 'required|array',
            'offices.*' => 'exists:offices,id',
        ]);

        $input = $request->all();
        if (! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        $user = User::find($id);
        $user->update($input);

        // Sync roles
        $roleIds = Role::whereIn('name', $request->input('roles'))->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        // Sync offices
        $user->offices()->sync($request->input('offices'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}

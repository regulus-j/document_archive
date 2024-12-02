<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Mail\UserInvite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $users = User::latest()->paginate(5);
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
            $query->where('first_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->role . '%');
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
        return view('users.create', compact('roles'));
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
            //'password' => 'required|same:password_confirmation|string|min:8',
            'roles' => 'required'
        ]);

        $user = User::create([
            'first_name'=> $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'password' => bcrypt($temp_pass),
            ]);
        
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
        $userRole = $user->roles->pluck('name', 'name')->all();
    
        return view('users.edit', compact('user', 'roles', 'userRole'));
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
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
    
        $user->assignRole($request->input('roles'));
    
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
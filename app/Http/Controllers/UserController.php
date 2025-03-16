<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use App\Models\CompanyAccount;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $users = User::with('company')->paginate(10); // Fetch users with pagination
        $roles = Role::all(); // Fetch all roles
        return view('users.index', compact('users', 'roles'));
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
                $q->where('id', $request->role);
            });
        }

        $users = $query->paginate(5);

        return view('users.index', compact('users', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function __construct()
    {
        $this->middleware('auth'); // Ensures user is authenticated
    
        // Restrict user management to admins only
        $this->middleware('role:admin|superadmin')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::all(); // Fetch all roles
        $userCompany = CompanyAccount::all(); // Fetch all companies
        $offices = Office::all(); // Fetch all offices

        return view('users.create', compact('roles', 'userCompany', 'offices'));
    }
    

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',  // Validate using role IDs
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign roles using sync (expects role IDs)
        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show($id): View
    {
        $company = Company::with('users')->findOrFail($id);
        return view('users.show', compact('company'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Fetch all roles
        $userRoles = $user->roles->pluck('id')->toArray(); // Fetch user's roles as IDs
        $userCompany = CompanyAccount::all(); // Fetch all companies
        $offices = Office::all(); // Fetch all offices

        return view('users.edit', compact('user', 'roles', 'userRoles', 'userCompany', 'offices'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id', // Validate using role IDs
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $user->roles()->sync($request->roles);

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

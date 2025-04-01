<?php

namespace App\Http\Controllers;

use App\Mail\UserInvite;
use App\Models\Office;
use App\Models\User;
use App\Models\CompanyAccount;
use App\Models\Plan;
use App\Models\CompanyUser;
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
     * Constructor to set up middleware
     */
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $company = CompanyAccount::where('user_id', auth()->id())->first();
        $users = $company ? $company->employees()->paginate(5) : collect();

        $roles = Role::all();

        if (auth()->user()->isSuperAdmin()) {
            return $this->showRegistered();
        }

        if (auth()->user()->isCompanyAdmin()) {
            $companyId = auth()->user()->companies()->first()->id;
            $users = User::whereHas('companies', function($query) use ($companyId) {
                $query->where('company_accounts.id', $companyId);
            })->paginate(5);
        }

        return view('users.index', compact('users', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function showRegistered(): View
    {
        $users = User::with(['companies.subscriptions.plan'])
            ->paginate(10); 

        $roles = Role::all();

        return view('admin.users-index', compact('users', 'roles'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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
        $userCompany = CompanyAccount::where('user_id', auth()->id())->get();
        $roles = Role::pluck('name', 'name')->all();
        $company = auth()->user()->companies()->first();
        $offices = Office::where('company_id', $company->id)->get();

        // Fetch users that belong to the same company & offices
        $users = User::whereHas('offices', function($query) use ($offices) {
            $query->whereIn('offices.id', $offices->pluck('id'));
        })->get();

        return view('users.create', compact('roles', 'offices', 'userCompany', 'users'));
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
            'offices' => 'required|array',
            'offices.*' => 'exists:offices,id',
            'roles' => 'required',
            'companies' => 'required|exists:company_accounts,id'
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

        CompanyUser::create([
            'company_id' => $request->companies,
            'user_id'     => $user->id,
        ]);

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
        $userCompany = CompanyAccount::all();

        return view('users.edit', compact('user', 'roles', 'userRoles', 'offices', 'userOffices', 'userCompany'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
    
        // Prepare validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'roles' => 'required|array',
            'companies' => 'required|exists:company_accounts,id'
        ];
    
        // Conditionally require offices field
        if (!$user->hasRole('company-admin')) {
            $rules['offices'] = 'required|array'; // Only require if not admin
        }
    
        // Validate the request with the prepared rules
        $request->validate($rules);
    
        // Update user details
        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
    
        // Sync roles
        $roleIds = Role::whereIn('name', $request->input('roles'))->pluck('id')->toArray();
        $user->roles()->sync($roleIds);
    
        // Sync offices if not admin
        if (!$user->hasRole('company-admin')) {
            $user->offices()->sync($request->input('offices'));
        }
    
        // Update company association
        $companyId = $request->companies[0]; // Get the first selected company ID
        CompanyUser::where('user_id', $user->id)->update([
            'company_id' => $companyId // Use the single company ID
        ]);
    
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }
    public function getUsersByOffice(Request $request)
{
    $officeId = $request->query('office_id');
    $users = User::whereHas('offices', function($query) use ($officeId) {
        $query->where('offices.id', $officeId);
    })->get();
    return response()->json($users);
}

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Check if the user is trying to delete themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if the user is an admin trying to delete another admin
        if (auth()->user()->hasRole('super-admin') && $user->hasRole('super-admin')) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete another super admin account.');
        }

        // Delete user's company associations
        CompanyUser::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}

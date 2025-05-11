<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    function __construct()
    {   
        // Check if user has any of these permissions
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

        public function index(Request $request): View
    {
        // If the user is a superadmin, show all roles
        if (auth()->user()->isSuperAdmin()) {
            $query = Role::orderBy('id', 'DESC');
            $filterRoles = Role::all();
        } elseif (auth()->user()->hasRole('company-admin')) {
            $query = Role::where('name', '!=', 'super-admin')->orderBy('id', 'DESC');
            $filterRoles = Role::where('name', '!=', 'super-admin')->get();
        } else {
            $query = Role::query()->whereRaw('0=1'); // No roles
            $filterRoles = collect();
        }

        // Filtering by role name if requested
        if ($request->filled('role_search')) {
            $query->where('name', 'like', '%' . $request->input('role_search') . '%');
        }
        $roles = $query->paginate(5);

        return view('roles.index', compact('roles', 'filterRoles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create(): View
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
    
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);
    
        return redirect()->route('roles.index')
                        ->with('success', 'Role created successfully');
    }

    public function show($id): View
    {
        $role = Role::find($id);
        
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
    
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id): View
    {
        $role = Role::find($id);
        
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required',         
        ]);
    
        $role = Role::find($id);
        
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
    
        $role->syncPermissions($permissionsID);
    
        return redirect()->route('roles.index')
                        ->with('success', 'Role updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $role = Role::find($id);
        
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
                        ->with('success', 'Role deleted successfully');
    }
}

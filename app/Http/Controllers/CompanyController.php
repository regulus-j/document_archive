<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CompanyAccount;
use App\Models\CompanyAddress;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index()
    {
        // Eager-load 'address' and 'admin' to display admin details in the view.
        $companies = CompanyAccount::with(['address', 'admin'])
            ->where('user_id', auth()->id())
            ->paginate(10);
        return view('companies.index', compact('companies'));
    }
    
    public function create()
    {
        $users = User::all(); // Fetch all users to select an admin
        return view('companies.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'    => 'required|string|max:255|unique:company_accounts,company_name',
            'registered_name' => 'required|string|max:255|unique:company_accounts,registered_name',
            'company_email'   => 'required|string|email|max:255|unique:company_accounts,company_email',
            'company_phone'   => 'required|string|max:255|unique:company_accounts,company_phone',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'zip_code'        => 'required|string|max:255',
            'country'         => 'required|string|max:255',
            'admin_id'        => 'required|exists:users,id', // Ensure admin exists
            'office_id'       => 'nullable|exists:offices,id', // Offices are now optional
        ]);

        // Create the company account
        $company = CompanyAccount::create([
            'user_id'         => auth()->id(),
            'company_name'    => $request->company_name,
            'registered_name' => $request->registered_name,
            'company_email'   => $request->company_email,
            'company_phone'   => $request->company_phone,
            'admin_id'        => $request->admin_id, // Assign the admin user
            'office_id'       => $request->office_id ?? null, // Only save if provided
        ]);

        // Create company address
        CompanyAddress::create([
            'company_id' => $company->id,
            'address'    => $request->address,
            'city'       => $request->city,
            'state'      => $request->state,
            'zip_code'   => $request->zip_code,
            'country'    => $request->country,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company added successfully!');
    }

    public function show($id)
    {
        $company = CompanyAccount::with(['offices', 'address', 'admin'])->findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = CompanyAccount::with('address')->findOrFail($id);
        $users = User::all(); // Get all users
        $allUsers = User::whereNotIn('id', [$company->admin_id])->get(); // Exclude current admin

        return view('companies.edit', compact('company', 'users', 'allUsers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'company_name'    => 'required|string|max:255',
            'registered_name' => 'required|string|max:255',
            'company_email'   => 'required|email|max:255',
            'company_phone'   => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'state'           => 'required|string|max:255',
            'zip_code'        => 'required|string|max:20',
            'country'         => 'required|string|max:255',
            'admin_id'        => 'required|exists:users,id', // Ensure valid admin
            'office_id'       => 'nullable|exists:offices,id', // Now optional
        ]);

        $company = CompanyAccount::findOrFail($id);
        $company->update([
            'company_name'    => $validated['company_name'],
            'registered_name' => $validated['registered_name'],
            'company_email'   => $validated['company_email'],
            'company_phone'   => $validated['company_phone'],
            'admin_id'        => $validated['admin_id'],
            'office_id'       => $validated['office_id'] ?? null, // Only update if provided
        ]);

        // Update or create the address record
        if ($company->address) {
            $company->address->update($validated);
        } else {
            $company->address()->create($validated);
        }

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(CompanyAccount $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
    public function manage(Company $company)
{
    return view('companies.manage', compact('company'));
}
    
    


   
    

public function updateLogo(Request $request, $id)
{
    $request->validate([
        'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $company = Company::findOrFail($id);

    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('logos', 'public');
        $company->logo = $path;
        $company->save();
    }

    return back()->with('success', 'Logo updated successfully!');
}
public function updateName(Request $request, $id)
{
    $request->validate([
        'site_name' => 'required|string|max:255',
    ]);

    $company = Company::findOrFail($id);
    $company->site_name = $request->site_name;
    $company->save();

    return back()->with('success', 'Site name updated successfully!');
}

public function updateTheme(Request $request, $id)
{
    $request->validate([
        'color_theme' => 'required|string|in:blue,green,red',
    ]);

    $company = Company::findOrFail($id);
    $company->color_theme = $request->color_theme;
    $company->save();

    return back()->with('success', 'Color theme updated successfully!');
}


    

}

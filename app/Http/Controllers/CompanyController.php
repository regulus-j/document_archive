<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CompanyAccount;
use App\Models\CompanyAddress;


class CompanyController extends Controller
{
    public function index()
    {
        // Get all companies.
        $companies = CompanyAccount::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        // Show the form for creating a new company.
        return view('companies.create');
    }

    public function store(Request $request)
    {
        // Validate and store a newly created company.
        $validated = $request->validate([
            'user_id'         => 'required|exists:users,id',
            'company_name'    => 'required|string|max:255',
            'registered_name' => 'required|string|max:255',
            'company_email'   => 'required|email|max:255',
            'company_phone'   => 'required|string|max:20',
        ]);

        if($request->part == '2') {
            $addressValidated = $request->validate([
            'address'  => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'state'    => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country'  => 'required|string|max:255',
            ]);
            
            // Update the company with address details
            CompanyAccount::create($validated);
            $company = CompanyAccount::latest()->first();
            $company->addresses()->create($addressValidated);
            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        }
        return redirect()->route('companies.create')->with('success', 'Company created successfully.')->withInput();
    }

    public function show(CompanyAccount $company)
    {
        // Display the specified company with its address
        $address = $company->addresses()->first();
        return view('companies.show', compact('company', 'address'));
    }

    public function edit(CompanyAccount $company)
    {
        if(auth()->user()->isAdmin())
        {
           $users = User::paginate(10);
        }
        // Show the form for editing the specified company.
        return view('companies.edit', compact('company', 'users'));
    }

    public function update(Request $request, CompanyAccount $company)
    {
        // Validate and update the specified company.
        $validated = $request->validate([
            'user_id'         => 'required|exists:users,id',
            'company_name'    => 'required|string|max:255',
            'registered_name' => 'required|string|max:255',
            'company_email'   => 'required|email|max:255',
            'company_phone'   => 'required|string|max:20',
        ]);

        $company->update($validated);
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(CompanyAccount $company)
    {
        // Remove the specified company from storage.
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function userCompanies($userId)
    {
        $companies = CompanyAccount::where('user_id', $userId)->with('address')->get();
        return view('companies.userManaged', compact('companies'));
    }

}

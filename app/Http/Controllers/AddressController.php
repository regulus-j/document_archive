<?php

namespace App\Http\Controllers;

use App\Models\CompanyAddress;
use App\Models\CompanyAccount;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = CompanyAddress::with('company')->paginate(10);
        return view('company_addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = CompanyAccount::all();
        return view('company_addresses.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:company_accounts,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
        ]);

        CompanyAddress::create($validated);

        return redirect()->route('company_addresses.index')
            ->with('success', 'Company address created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyAddress $companyAddress)
    {
        return view('company_addresses.show', compact('companyAddress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyAddress $companyAddress)
    {
        $companies = CompanyAccount::all();
        return view('company_addresses.edit', compact('companyAddress', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyAddress $companyAddress)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:company_accounts,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
        ]);

        $companyAddress->update($validated);

        return redirect()->route('company_addresses.index')
            ->with('success', 'Company address updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyAddress $companyAddress)
    {
        $companyAddress->delete();

        return redirect()->route('company_addresses.index')
            ->with('success', 'Company address deleted successfully.');
    }
}
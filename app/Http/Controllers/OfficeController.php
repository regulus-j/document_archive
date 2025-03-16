<?php

namespace App\Http\Controllers;

use App\Models\CompanyAccount;  // ✅ Ensure this is the correct model
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\User;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Fetch only offices that belong to this company
        $offices = Office::where('company_id', $companyId)->get();

        return view('offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
   
public function create()
{
    $user = auth()->user();
    $adminCompanyId = $user->company_id;

    // Fetch only offices under the same company
    $offices = Office::where('company_id', $adminCompanyId)->pluck('name', 'id');

    // Fetch users who belong to the same company
    $users = User::where('company_id', $adminCompanyId)->get();

    // Fetch companies only if the admin is not tied to one
    $companies = $adminCompanyId ? null : CompanyAccount::pluck('name', 'id');

    return view('offices.create', compact('offices', 'companies', 'adminCompanyId', 'users'));
}

    /**
     * Store a newly created office in the database.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'parent_office_id' => 'nullable|exists:offices,id',
        'company_id' => 'required|exists:company_accounts,id',
        'user_id' => 'nullable|exists:users,id', // ✅ Validate user_id if selected
    ]);

    // Create the new office
    $office = Office::create([
        'name' => $request->name,
        'parent_office_id' => $request->parent_office_id,
        'company_id' => auth()->user()->company_id, // Associate with the logged-in user's company
    ]);

    // Assign user to office
    if ($request->user_id) {
        $user = User::find($request->user_id);
        if ($user) {
            $user->office_id = $office->id;
            $user->save();
        }
    }

    return redirect()->route('offices.index')->with('success', 'Office created successfully!');
}

 

 




    /**
     * Show the specified office.
     */
    public function show(Office $office)
    {
        if ($office->company_id != auth()->user()->company_id) {
            return redirect()->route('offices.index')->with('error', 'Unauthorized access.');
        }

        return view('offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified office.
     */
    public function edit(Office $office)
    {
        if ($office->company_id != auth()->user()->company_id) {
            return redirect()->route('offices.index')->with('error', 'Unauthorized access.');
        }

        $offices = Office::where('company_id', auth()->user()->company_id)
                         ->where('id', '!=', $office->id)
                         ->pluck('name', 'id');

        return view('offices.edit', compact('office', 'offices'));
    }

    /**
     * Update the specified office in storage.
     */
    public function update(Request $request, Office $office)
    {
        if ($office->company_id != auth()->user()->company_id) {
            return redirect()->route('offices.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_office_id' => 'nullable|exists:offices,id',
        ]);

        $office->update([
            'name' => $request->name,
            'parent_office_id' => $request->parent_office_id,
        ]);

        return redirect()->route('offices.index')->with('success', 'Office updated successfully.');
    }

    /**
     * Remove the specified office.
     */
    public function destroy(Office $office)
    {
        if ($office->company_id != auth()->user()->company_id) {
            return redirect()->route('offices.index')->with('error', 'Unauthorized access.');
        }

        if ($office->childOffices()->exists()) {
            return back()->with('error', 'Cannot delete an office with child offices.');
        }
        if ($office->users()->exists()) {
            return back()->with('error', 'Cannot delete an office with users.');
        }
        if ($office->sentTransactions()->exists() || $office->receivedTransactions()->exists()) {
            return back()->with('error', 'Cannot delete an office with associated transactions.');
        }

        $office->delete();

        return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
    }

//     public function hasCompany($id)
// {
//     $office = Office::with('company')->findOrFail($id);
    
//     return view('offices.show', compact('office'));
// }

}

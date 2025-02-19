<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource with parent office name if exists.
     */
    public function index()
    {
        $company = auth()->user()->companies()->first();
        $offices = Office::where('company_id', $company->id)->get();

        return view('offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $offices = Office::all()->pluck('name', 'id');
        $companies = auth()->user()->companies()->pluck('company_name', 'id');

        return view('offices.create', compact('offices', 'companies'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_office_id' => 'nullable|exists:offices,id',
            'company_id' => 'required|exists:company_accounts,id',

        ]);
    
        $office = Office::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'parent_office_id' => $request->parent_office_id,
        ]);
    
        return redirect()->route('office.index')
            ->with('success', 'Office created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
        $office = Office::with('parentOffice')->find($office->id);
        return view('offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        $office = Office::with('parentOffice')->find($office->id);
        $offices = Office::where('id', '!=', $office->id)->get();  // Exclude current office
        
        return view('offices.edit', compact('office', 'offices'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_office_id' => 'nullable|exists:offices,id',
        ]);

        try {
            $office->update([
                'name' => $request->name,
                'parent_office_id' => $request->parent_office_id,
            ]);

            return redirect()->route('office.index')->with('success', 'Office updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating office');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        try {
            if ($office->childOffices()->count() > 0) {
                return back()->with('error', 'Cannot delete office with child offices.');
            }
            if ($office->users()->count() > 0) {
                return back()->with('error', 'Cannot delete office with associated users.');
            }
            if ($office->sentTransactions()->count() > 0 || $office->receivedTransactions()->count() > 0) {
                return back()->with('error', 'Cannot delete office associated with documents.');
            }
            
            $office->delete();
            return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting office: ' . $e->getMessage());
        }
    }

    
}

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
        $offices = Office::with('parentOffice')->get();

        return view('offices.index', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $offices = Office::pluck('name', 'id')->all();
        return view('offices.index', compact('offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'office_id' => 'exists:offices,id',
        ]);

        try {
            if ($request->office_id) {
                Office::create([
                    'name' => $request->name,
                    'office_id' => $request->office_id,
                ]);
            } else {
                Office::create([
                    'name' => $request->name,
                ]);
            }

            return back()->with('success', 'Office created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating office');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        //
    }
}

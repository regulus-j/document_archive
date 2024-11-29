<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($search = null)
    {
        if ($search) {
            $teams = Team::where('name', 'like', '%' . $search . '%')->get();
        } else {
            $teams = Team::all();
        }
        return response()->json($teams);
    }

    public function ajaxSearch(Request $request)
    {
        $search = $request->input('search');

        $teams = Team::where('name', 'like', '%' . $search . '%')->get();

        return response()->json(['teams' => $teams]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}

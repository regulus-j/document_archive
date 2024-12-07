<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $recentTransactions = Documents::transactions()->latest()->take(5)->get();

        return view('dashboard', compact('recentTransactions'));
    }
}

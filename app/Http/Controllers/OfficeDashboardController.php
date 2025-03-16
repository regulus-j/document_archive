<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class OfficeDashboardController extends Controller
{
    
public function officeDashboard()
{
    // Check if the Document model exists and is used properly
    $totalDocuments = \App\Models\Document::count(); 

    return view('office.dashboard', compact('totalDocuments'));
}
}

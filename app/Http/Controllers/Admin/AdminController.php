<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User; // Import the User model

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDocuments = Document::count(); 
        $totalUsers = User::count(); // Add this line

        return view('admin.dashboard', compact('totalDocuments', 'totalUsers'));
    }
}

<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManualController extends Controller
{
    public function show()
    {
        return view('userManual.manual'); // Ensure this Blade file exists
    }
}

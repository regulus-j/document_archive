<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrialController extends Controller
{
    public function start()
    {
        $user_id = Auth::id(); // ✅ Get the authenticated user's ID

        // Check if the user exists in `company_users`
        $companyUser = DB::table('company_users')->where('user_id', $user_id)->first();

        if (!$companyUser) {
            return redirect()->route('dashboard')->with('error', 'You are not assigned to a company.');
        }

        // ✅ Update `trial_ends_at` in `company_users`
        DB::table('company_users')
            ->where('user_id', $user_id)
            ->update(['trial_ends_at' => now()->addDays(14)]);

        return redirect()->route('dashboard')->with('success', 'Your trial has started!');
    }
}

<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrialController extends Controller
{
    public function start()
    {
        try {
            $user_id = Auth::id(); // ✅ Get the authenticated user's ID

            // Log the user ID
            Log::info('Starting trial for user', ['user_id' => $user_id]);

            // Check if the user exists in `company_users`
            $companyUser = DB::table('company_users')->where('user_id', $user_id)->first();

            if (!$companyUser) {
                Log::warning('User not assigned to a company', ['user_id' => $user_id]);
                return redirect()->route('dashboard')->with('error', 'You are not assigned to a company.');
            }

            // ✅ Update `trial_ends_at` in `company_users`
            $trialEndDate = now()->addDays(14);
            DB::table('company_users')
                ->where('user_id', $user_id)
                ->update(['trial_ends_at' => $trialEndDate]);

            // Log the trial start
            Log::info('Trial started successfully', [
                'user_id' => $user_id,
                'trial_ends_at' => $trialEndDate,
            ]);

            return redirect()->route('dashboard')->with('success', 'Your trial has started!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error starting trial', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('dashboard')->with('error', 'An error occurred while starting your trial. Please try again later.');
        }
    }
}

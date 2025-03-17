<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserManaged; // Use the correct model

class UserManagedController extends Controller
{
    public function index($id)
    {
        // Fetch company using Eloquent (instead of DB::table)
        $company = UserManaged::with(['users', 'offices'])->find($id);

        // If company is not found, return 404 error
        if (!$company) {
            abort(404, 'Company not found.');
        }

        return view('userManaged', compact('company'));
    }

    public function updateLogo(Request $request, $id)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $company = UserManaged::findOrFail($id);

        // Delete old logo if exists
        if ($company->logo) {
            Storage::delete($company->logo);
        }

        // Upload new logo
        $logoPath = $request->file('logo')->store('logos', 'public');
        $company->logo = $logoPath;
        $company->save();

        return redirect()->back()->with('success', 'Company logo updated successfully!');
    }

    public function updateName(Request $request, $id)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        $company = UserManaged::findOrFail($id);
        $company->site_name = $request->site_name;
        $company->save();

        return redirect()->back()->with('success', 'Company name updated successfully!');
    }

    public function updateTheme(Request $request, $id)
    {
        $request->validate([
            'color_theme' => 'required|in:blue,green,red',
        ]);

        $company = UserManaged::findOrFail($id);
        $company->color_theme = $request->color_theme;
        $company->save();

        return redirect()->back()->with('success', 'Theme updated successfully!');
    }
}

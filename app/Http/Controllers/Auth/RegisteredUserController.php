<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyAccount;
use App\Models\CompanyAddress;
use App\Models\CompanyUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'registered_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'company_phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:admin,user'],
        ]);

        // 🔹 Use the renamed method
        $user = $this->createUser($request->all());

        event(new Registered($user));
        Auth::login($user);

        // Pre-emptive code for company registration
        $company = CompanyAccount::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'registered_name' => $request->registered_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
        ]);

        $companyAddress = CompanyAddress::create([
            'company_id' => $company->id,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ]);

        CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('dashboard');
    }

    // 🔹 Renamed method
    protected function createUser(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($data['role'] === 'admin') {
            $user->assignRole('admin');
        } elseif ($data['role'] === 'user') {
            $user->assignRole('user');
        }

        return $user;
    }
}



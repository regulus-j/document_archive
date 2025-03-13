<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->companies()->exists()) {
            return redirect()->route('companies.create')
                ->with('error', 'Please create a company first.');
        }

        return $next($request);
    }
}
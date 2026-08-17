<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotCustomer
{
    /**
     * Customer role thakle backend dashboard e dhukte parবে না।
     * Shudhu Customer chara baki shob role (Super Admin, Admin, etc.) dhukte parবে।
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('Customer')) {
            abort(403, 'Access denied. You do not have permission to access this page.');

            // Ba direct home page e pathai dite chaile eita use koro:
            // return redirect('/')->with('error', 'Apni ei page ta dekhte parbe na.');
        }

        return $next($request);
    }
}

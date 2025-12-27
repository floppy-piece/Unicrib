<?php
//this handles property but i named landlord and am too lazy to change it enjoy
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLandlordVerified
{

     /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   /* public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->category !== 'landlord') {
            abort(403);
        }

        if (!$user->hasValidLandlordAccess()) {
            return redirect()->route('payments.landlord.form')
                ->with('error', 'Verify your account with a KES 500 semester fee before adding properties.');
        }
        return $next($request);
    }*/
   
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Optional: still ensure only landlords can access
        if (!$user || $user->category !== 'landlord') {
            abort(403);
        }

        // Get the Property from the route (assumes route model binding: {property})
        $property = $request->route('property');

        // If not using implicit binding, uncomment:
        // $property = Property::findOrFail($request->route('property'));

        // Check property verification via isVerificationValid()
        if (!$property || !$property->isVerificationValid()) {
            return redirect()->route('payments.landlord.form')
                ->with('error', 'This property is not verified yet.Verify your account with a KES 500 semester fee before adding properties.');
        }

        return $next($request);
    }
    
}


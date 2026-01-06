<?php
//this handles property but i named landlord and am too lazy to change it enjoy
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Property;

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
   
    
    /*public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Still make sure only landlords reach these routes
        if (!$user || $user->category !== 'landlord') {
            abort(403);
        }

        // Get property, assuming route model binding: {property}
        $property = $request->route('property');

        if (!$property instanceof Property) {
            // if using ID not binding:
            $property = Property::find($request->route('property'));
        }

        // Property must exist and be verified
        if (!$property || !$property->isVerificationValid()) {
            return redirect()->route('payments.landlord.form')
                ->with('error', 'This property is not verified or its verification has expired.');
        }

        return $next($request);
    }*/
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only landlords may access these routes
        if (! $user || $user->category !== 'landlord') {
            abort(403);
        }

        // Retrieve the property from the route (supports model binding or ID)
        $routeProperty = $request->route('property');

        if ($routeProperty instanceof Property) {
            $property = $routeProperty;
        } else {
            $propertyId = is_numeric($routeProperty) ? (int) $routeProperty : null;
            $property   = $propertyId ? Property::find($propertyId) : null;
        }

        // Ensure a valid property exists
        if (! $property) {
            abort(404);
        }

        // Property must be verified and not expired
        if (! $property->isVerificationValid()) {
            return redirect()
                ->route('payments.landlord.form')
                ->with('error', 'This property is not verified or its verification has expired.');
        }

        // Optionally: make the property available downstream
        $request->attributes->set('property', $property);

        return $next($request);
    }
    
}


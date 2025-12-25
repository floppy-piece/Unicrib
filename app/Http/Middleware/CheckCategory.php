<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCategory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$categories): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has the required category
        if (!in_array($user->category, $categories)) {
            // Redirect based on user's actual category
            if ($user->category === 'student') {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have access to the landlord area.');
            } elseif ($user->category === 'landlord') {
                return redirect()->route('landlord.dashboard')
                    ->with('error', 'You do not have access to the student area.');
            }
            
            // Fallback for invalid categories
            return redirect('/')
                ->with('error', 'Invalid user category.');
        }

        return $next($request);
    }
}
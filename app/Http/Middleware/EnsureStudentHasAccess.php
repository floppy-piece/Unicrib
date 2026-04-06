<?php
//EnsureStudentHasAccess.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Property;

class EnsureStudentHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->category !== 'student') {
            abort(403);
        }

        if (!$user->hasValidStudentAccess()) {
            return redirect()->route('payments.student.form')
                ->with('error', 'You need to pay KES 50 to view property details (valid for 6 hours).');
        }
        return $next($request);
    }
}

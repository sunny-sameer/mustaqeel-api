<?php

namespace App\Http\Middleware\Api\V1;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesExceptApplicantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json(['message'=>'Unauthorized action.'],403);
        }

        $user = User::find(auth()->id());
        $role = $user->roles->pluck('type')->first();

        if ($role == 'applicant') {
            return response()->json(['message'=>'Unauthorized action.'],403);
        }

        return $next($request);
    }
}

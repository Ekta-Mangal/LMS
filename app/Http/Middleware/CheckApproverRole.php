<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproverRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !in_array($user->role, ['Admin', 'L3'])) {
            return redirect()->back()->with([
                'error' => 'Unauthorized: You do not have access.',
                'alert-type' => 'error'
            ]);
        }

        return $next($request);
    }
}
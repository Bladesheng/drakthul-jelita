<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
	/**
	 * Handle an incoming request.
	 *
	 * @param Closure(Request): (Response) $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if ($request->cookie('admin_token') === config('auth.admin_password')) {
			$request->attributes->set('isAdmin', true);
		}

		return $next($request);
	}
}

<?php

namespace App\Http\Middleware;

use App\Library\Jump;
use App\Models\AdminLog;
use App\Models\AdminMenu;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    use Jump;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        return $next($request);
    }



}

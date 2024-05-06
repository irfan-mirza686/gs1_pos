<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckMemberExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd(Auth::user()->end_date);
        // dd(date('Y-m-d'));
        if (date('Y-m-d') > Auth::user()->end_date ) {
            // dd("current is bigger then end date");
            Auth::logout();
            return redirect(route('login'))->with('flash_message_error','Your session is expired');
        }
        return $next($request);
    }
}

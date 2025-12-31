<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Session;

class AdminMiddleware {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/admin/login');
            }
        }

        if (Auth::user()->user_type == 'user') {
            Auth::logout();
            flash("You don't have rights to access this admin area.")->error();
            return redirect()->guest('/admin/login');
        }
        if (Auth::user()->status != 'active') {
            Auth::logout();
            flash("Your account is blocked or inactive, Kindly contact to administrator to activate again.")->error();
            return redirect()->guest('/admin/login');
        }

        return $next($request);
    }

}

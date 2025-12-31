<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Session;

class CompanyMiddleware {

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
        if (Auth::guard('company_user')->check() == false || is_null(Auth::guard('company_user')->user())) {
            flash("Kindly login first to access this page.")->error();
            return redirect()->guest('login');
        }

        // Check for Active Company Only

        $company = Auth::guard('company_user')->user();
        if ($company->status == 'Waiting for activation') {
            Auth::guard('company_user')->logout();
            flash("You have not activated your account, Kindly check your inbox for activation email or contact Administrator")->error();
            return redirect()->guest('login');
        }

        /* else if (Auth::guard('company')->user()->status != 'active') {
          Auth::logout();
          flash("Your account is blocked or inactive, Kindly contact to administrator to activate again.")->error();
          return redirect()->guest('login');
          } */

        return $next($request);
    }

}

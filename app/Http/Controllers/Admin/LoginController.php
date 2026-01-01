<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Auth;
use Session;
use Validator;
// Models
use App\Models\User;
use App\Models\Custom;

class LoginController extends Controller {

    public function __construct() {
        // Redirect Paths
        $this->paths = [
            'dashboard' => url('admin/dashboard'),
            'login' => url('admin/login'),
        ];

        $this->view_base = 'admin.login.';
    }

    // Login Form
    public function login() {
        // redirect if already logged in
        if (Auth::check()) {
            return redirect($this->paths['dashboard']);
        }
        return view($this->view_base . 'login');
    }

    // Login Post Method
    public function postLogin(Request $request) {
        // redirect if already logged in
        if (Auth::check()) {
            return redirect($this->paths['dashboard']);
        }

        // Google Recaptcha Check
        // $resultJson = Custom::check_captcha($request);
        // if ($resultJson->success != true) {
        //     flash('Captcha Error, Please reload page and try again.')->error();
        //     return back();
        // }

        $validator = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return redirect($this->paths['login'])
                            ->withErrors($validator)
                            ->withInput();
        } else {

            if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                // Activity Log
                activity()->performedOn(Auth::user())->log('Logged In');
                return redirect()->intended($this->paths['dashboard']);
            }

            return redirect($this->paths['login'])
                            ->withInput($request->only('email'))
                            ->withErrors([
                                'email' => 'Email and password do not match',
            ]);
        }
    }

    /* logout method [GET] - (start) */

    public function getLogout() {
        // Activity Log
        //activity()->performedOn(Auth::user())->log('Logged Out');
        Auth::logout();
        //Session::flush();
        return redirect($this->paths['login']);
    }

}

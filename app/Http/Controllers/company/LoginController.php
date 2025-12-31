<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\User\ForgotUsernameMail;
use App\Mail\Company\CompanyMail;
use App\Mail\Followup\RegisteredMemberFollowUpMail;
use Illuminate\Support\Facades\Mail;
use App\Rules\ValidRecaptcha;
use Auth;
use Session;
use Validator;
// Models
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyContactView;
use App\Models\Custom;

class LoginController extends Controller {

    public function __construct() {
        // Redirect Paths
        $this->paths = [
            'dashboard' => url('dashboard'),
            'login' => url('login'),
        ];
        $this->view_base = 'company.login.';
    }

    // Login Form
    public function getLogin() {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::guard('company_user')->user())) {
            return redirect($this->paths['dashboard']);
        }
        return view($this->view_base . 'login');
    }

    // Login Post Method
    public function postLogin(Request $request) {
        //This is a fix for Masquerading from admin
        Session::forget('company_mask');
        
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::guard('company_user')->user())) {
            return redirect($this->paths['dashboard']);
        }

        // Google Recaptcha Check
        /* $resultJson = Custom::check_captcha($request);
          if ($resultJson->success != true) {
          flash('Captcha Error, Please reload page and try again.')->error();
          return back();
          } */

        $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'password' => 'required|min:6|max:50',
                    'g-recaptcha-response' => ['required', new ValidRecaptcha]
        ]);

        if ($validator->fails()) {
            return redirect($this->paths['login'])
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $is_email = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL);
            $check_field = 'username';
            if ($is_email != false) {
                $check_field = 'email';
            }

            if (Auth::guard('company_user')->attempt([$check_field => $request->get('username'), 'password' => $request->get('password')])) {

                activity()->performedOn(Auth::guard('company_user')->user())->log('Logged In');
                // check for Company Status
                $company_obj = \App\Models\Company::find(Auth::guard('company_user')->user()->company_id);

                if ($company_obj->status == 'Registered') {
                    Auth::guard('company_user')->logout();
                    Session::flush();

                    $flash_var = '<div class="text-center">Please confirm your account to login. <br/>';
                    $flash_var .= '<a class="resend-activation-link text-danger" href="' . route('resend-activation-link', ['company_id' => $company_obj->id]) . '">Resend Confirmation Email</a></div>';

                    flash($flash_var)->error();
                    return redirect($this->paths['login']);
                }

                return redirect()->intended($this->paths['dashboard']);
            }
            return redirect($this->paths['login'])
                            ->withInput($request->only('email'))
                            ->withErrors([
                                'username' => 'Username/Email and password do not match',
            ]);
        }
    }

    /* logout method [GET] - (start) */

    public function getLogout() {
        CompanyContactView::where('session_id', Session::getId())->delete();
        // Activity Log
        //activity()->performedOn(Auth::user())->log('Logged Out');
        Auth::guard('company_user')->logout();
        //Session::flush();
        return redirect($this->paths['login']);
    }

    /* Forget Password */

    public function forgot_password() {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::user())) {
            return redirect($this->paths['dashboard']);
        }
        return view($this->view_base . 'forgot_password');
    }

    public function postForgotPassword(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::user())) {
            return redirect($this->paths['dashboard']);
        }

        $validator = Validator::make($request->all(), [
                    'email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            $company_user = CompanyUser::where('email', $requestArr['email'])->orWhere('username', $requestArr['email'])->active()->first();

            if (!is_null($company_user)) {
                /* $newPassword = Custom::getRandomString(6);
                  $company_user->password = bcrypt($newPassword); */

                $forgot_password_key = Custom::getRandomString(48);
                $company_user->forgot_password_key = $forgot_password_key;
                $company_user->save();

                $companyObj = Company::find($company_user->company_id);
                $web_settings = Custom::getSettings();

                $mail_id = '3';
                $replaceArr = [
                    'first_name' => $company_user->first_name,
                    'last_name' => $company_user->last_name,
                    'username' => $company_user->username,
                    'email' => $company_user->email,
                    'change_password_link' => route('reset-password', ['forgot_password_key' => $forgot_password_key]),
                    'login_link' => url('login'),
                    'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                    'request_generate_link' => $company_user->email,
                    'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                    'url' => url('forgot-password'),
                    'email_footer' => $company_user->email,
                    'copyright_year' => date('Y'),
                        //'main_service_category' => '',
                ];

                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('forgot-password'),
                ];

                Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceArr);
                Mail::to($company_user->email)->send(new CompanyMail($mail_id, $replaceArr));

                flash("Please check your inbox for reset password. If you don't have any email from us, please retry.")->success();
                return redirect('forgot-password');
            } else {
                flash("No User found with specified email or username.")->error();
                return back()->withInput();
            }
        }
    }

    public function reset_password($forgot_password_key) {
        // Find Company User With activation_key
        $companyUserObj = CompanyUser::where('forgot_password_key', $forgot_password_key)->first();

        if (is_null($companyUserObj)) {
            flash('Company User not found with specified key')->error();
            return redirect(url('login'));
        }

        $data = ['company_user' => $companyUserObj];
        return view($this->view_base . 'reset_password', $data);
    }

    public function post_reset_password(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::user())) {
            return redirect($this->paths['dashboard']);
        }

        $validator = Validator::make($request->all(), [
                    'forgot_password_key' => 'required',
                    'password' => 'required',
                    'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $company_user = CompanyUser::where('forgot_password_key', $requestArr['forgot_password_key'])->active()->first();

            if (!is_null($company_user)) {
                $company_user->password = bcrypt($requestArr['password']);
                $company_user->forgot_password_key = null;
                $company_user->save();

                flash("Password reset successfully. Login with your username/email and new password.")->success();
                return redirect(url('login'));
            } else {
                return back()->withInput()->withErrors(['email' => 'No User found with specified key.']);
            }
        }
    }

    /* Forget Password */

    public function forgot_username() {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::user())) {
            return redirect($this->paths['dashboard']);
        }
        return view($this->view_base . 'forgot_username');
    }

    public function postForgotUsername(Request $request) {
        // redirect if already logged in
        if (Auth::guard('company_user')->check() == true && !is_null(Auth::user())) {
            return redirect($this->paths['dashboard']);
        }

        $validator = Validator::make($request->all(), [
                    'email' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $company_user = CompanyUser::where('email', $requestArr['email'])->active()->first();

            if (!is_null($company_user)) {

                //Forgot Username User Email
                $mail_id = '94';
                $replaceWithArr = [
                    'first_name' => $company_user->first_name,
                    'last_name' => $company_user->last_name,
                    'username' => $company_user->username,
                    'company_name' => ''
                ];
                Mail::to($requestArr['email'])->send(new ForgotUsernameMail($mail_id, $replaceWithArr));

                flash("Please check your inbox for new username. If you don't have any email from us, please retry.")->success();
                return redirect('forgot-username');
            } else {
                return back()->withInput($requestArr['email'])->withErrors(['email' => 'No User found with specified email.']);
            }
        }
    }

    public function resendActivationLink($company_id) {
        //dd($company_id);
        $companyObj = Company::find($company_id);
        if (!is_null($companyObj)) {
            $company_user = CompanyUser::where('company_id', $company_id)->first();

            $web_settings = Custom::getSettings();
            if ($companyObj->membership_level_id == '1') {
                $url = url('preview-trial');
            } else if ($companyObj->membership_level_id == '3') {
                $url = url('accreditation');
            } else if ($companyObj->membership_level_id == '2') {
                $url = url('full-listing');
            }

            /* Registration confirmation mail to Company */
            $mail_id = "2";
            $replaceArr = [
                'company_name' => $companyObj->company_name,
                'account_type' => $companyObj->membership_level->title,
                'confirmation_link' => route('company-activation-link', ['activation_key' => $companyObj->activation_key]),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $company_user->email,
                'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                'url' => $url,
                'email_footer' => $company_user->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            Mail::to($company_user->email)->send(new RegisteredMemberFollowUpMail($mail_id, $replaceArr));

            flash('A new confirmation has been sent. Please check your email. If you are still having issues confirming your account registration, please contact member support at 720-445-4400')->success();
        } else {
            flash('Company not found with specified ID, Please try again')->error();
        }
    }

}

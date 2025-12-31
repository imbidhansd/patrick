<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
// Models
use App\Models\Custom;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyInformation;

class CompanyOwnerController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.owners.';
    }

    public function index() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $company_user = Auth::guard('company_user')->user();

        if ($company_user->company_user_type != 'company_super_admin') {
            flash('You can not access company owner page')->warning();
            return redirect(url('dashboard'));
        }

        $data = [
            'company_information' => CompanyInformation::where('company_id', $company_id)->first(),
        ];

        return view($this->view_base . 'index', $data);
    }

    public function invite(Request $request) {
        $num = $request->get('owner_num');
        $company_id = Auth::guard('company_user')->user()->company_id;
        $companyObj = Company::find($company_id);

        $company_information = CompanyInformation::where('company_id', $company_id)->first();

        $name_field = 'company_owner_' . $num . '_full_name';
        $email_field = 'company_owner_' . $num . '_email';
        $invitation_key_field = 'company_owner_' . $num . '_invitation_key';
        $status_field = 'company_owner_' . $num . '_status';

        if ($company_information->$status_field == 'pending' || $company_information->$status_field == 'invited') {


            if ($company_information->$status_field == 'pending') {
                $company_information->$invitation_key_field = strtoupper(Custom::getRandomString(50));
                $company_information->$status_field = 'invited';
                $company_information->save();
            }


            // Send Email Here [Start]
            $web_settings = $this->web_settings;
            $company_mail_id = "106"; /* Mail title: Company User Invitation */
            $replaceArr = [
                'owner_name' => $company_information->$name_field,
                'company_name' => $companyObj->company_name,
                'invitation_link' => url('register/company-owner/' . $company_information->$invitation_key_field),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
                'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $company_information->$email_field,
                'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('company-owners'),
                'email_footer' => $company_information->$email_field,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('company-owners')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceArr);
            Mail::to($company_information->$email_field)->send(new CompanyMail($company_mail_id, $replaceArr));
            // Send Email Here [End]

            flash($company_information->$name_field . ' has been invited')->success();
        } else {
            flash($company_information->$name_field . ' is already invited')->warning();
        }
        return back();
    }

    public function register_other_owner($invitation_key, Request $request) {

        if (Auth::guard('company_user')->check()) {
            flash('You are already signed in, Kindly use other browser to activate your account')->error();
        }

        $company_information_item = CompanyInformation::where(function ($query) use ($invitation_key) {
                    $query->where('company_owner_2_invitation_key', $invitation_key);
                    $query->orWhere('company_owner_3_invitation_key', $invitation_key);
                    $query->orWhere('company_owner_4_invitation_key', $invitation_key);
                })->first();

        if (!is_null($company_information_item)) {
            if ($company_information_item->company_owner_2_invitation_key == $invitation_key && $company_information_item->company_owner_2_status == 'invited') {
                $num = 2;
            } elseif ($company_information_item->company_owner_3_invitation_key == $invitation_key && $company_information_item->company_owner_3_status == 'invited') {
                $num = 3;
            } elseif ($company_information_item->company_owner_4_invitation_key == $invitation_key && $company_information_item->company_owner_4_status == 'invited') {
                $num = 4;
            } else {
                $num = 0;
            }

            $first_name = null;
            $last_name = null;
            $email = null;
            $phone_number = null;

            if ($num > 0) {
                $name_field = 'company_owner_' . $num . '_full_name';
                $email_field = 'company_owner_' . $num . '_email';
                $phone_field = 'company_owner_' . $num . '_phone';

                $name = explode(' ', $company_information_item->$name_field);
                if (is_array($name) && count($name) > 1) {
                    $first_name = $name['0'];
                    $last_name = $name['1'];
                } else {
                    $first_name = $company_information_item->$name_field;
                }
                $email = $company_information_item->$email_field;
                $phone_number = $company_information_item->$phone_field;
            }

            $data = [
                'company_item' => Company::find($company_information_item->company_id),
                'company_information' => $company_information_item,
                'num' => $num,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone_number' => $phone_number,
            ];
            return view($this->view_base . 'register', $data);
        } else {
            flash('You are already registerd! Please login to continue')->warning();
            return redirect('login');
        }
    }

    public function postRegister($invitation_key, Request $request) {
        $company_information_item = CompanyInformation::where('company_owner_2_invitation_key', $invitation_key)
                ->orWhere('company_owner_3_invitation_key', $invitation_key)
                ->orWhere('company_owner_4_invitation_key', $invitation_key)
                ->firstOrFail();

        if ($company_information_item->company_owner_2_invitation_key == $invitation_key && $company_information_item->company_owner_2_status == 'invited') {
            $num = 2;
        } elseif ($company_information_item->company_owner_3_invitation_key == $invitation_key && $company_information_item->company_owner_3_status == 'invited') {
            $num = 3;
        } elseif ($company_information_item->company_owner_4_invitation_key == $invitation_key && $company_information_item->company_owner_4_status == 'invited') {
            $num = 4;
        } else {
            $num = 0;
        }

        if ($num > 0) {
            $validation_arr = [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'user_telephone' => 'required',
                'username' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation_arr);

            if ($validator->fails()) {
                return response()->json(['status' => '0', 'Please fill all required fields']);
            }

            // check for unique Email
            if (CompanyUser::where('email', $request->get('email'))->count() > 0) {
                return ['status' => 0, 'message' => 'Email already exists'];
            }

            // check for unique Username
            if (CompanyUser::where('username', $request->get('username'))->count() > 0) {
                return ['status' => 0, 'message' => 'Username already exists'];
            }

            $company_user = CompanyUser::create([
                        'company_id' => $company_information_item->company_id,
                        'company_user_type' => 'company_admin',
                        'first_name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email'),
                        'user_telephone' => $request->get('user_telephone'),
                        'username' => $request->get('username'),
                        'password' => bcrypt($request->get('password')),
                        'status' => 'active'
            ]);

            $name_field = 'company_owner_' . $num . '_full_name';
            $email_field = 'company_owner_' . $num . '_email';
            $invitation_key_field = 'company_owner_' . $num . '_invitation_key';
            $status_field = 'company_owner_' . $num . '_status';
            $company_user_id = 'company_owner_' . $num . '_user_id';

            $company_information_item->$status_field = 'registered';
            $company_information_item->$company_user_id = $company_user->id;
            $company_information_item->$name_field = $request->get('first_name') . ' ' . $request->get('last_name');
            $company_information_item->$email_field = $request->get('email');
            $company_information_item->$invitation_key_field = '';
            $company_information_item->save();


            /* Company User Accepted invitation mail to Company User */
            $companyObj = Company::find($company_information_item->company_id);
            $web_settings = $this->web_settings;
            $company_mail_id = "107"; /* Mail title: Company User Accepted Invitation */
            $replaceArr = [
                'first_name' => $company_user->first_name,
                'account_type' => $companyObj->membership_level->title,
                'user_name' => $company_user->username,
                'user_email' => $company_user->email,
                'login_link' => url('login'),
                'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                'request_generate_link' => $company_user->email,
                'date' => $company_user->created_at->format(env('DATE_FORMAT')),
                'url' => url('company-owners'),
                'email_footer' => $company_user->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];
            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('company-owners')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceArr);
            Mail::to($company_information_item->$email_field)->send(new CompanyMail($company_mail_id, $replaceArr));

            /* Company User Accepted invitation mail to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                $admin_mail_id = "108"; /* Mail title: Company User Accepted Invitation - Admin */
                $adminReplaceArr = [
                    'owner_name' => $company_information_item->$name_field,
                    'company_name' => $companyObj->company_name,
                ];
                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
            }
        }

        // Send Email Here [Start]
        // Send Email Here [End]

        flash('Registration successful! Please login to continue')->success();
        return ['status' => 1];
    }

}

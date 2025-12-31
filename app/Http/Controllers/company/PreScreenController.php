<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use Validator;
// Models
use App\Models\Company;
use App\Models\State;
use App\Models\Custom;
use Carbon\Carbon;
use App\Models\PsqSession;
use App\Models\CompanyOwnerPreScreenQuestion;
use PDF;

class PreScreenController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.pre_screen.';
    }

    public function pre_screen_questions() {
        $companyUserObj = Auth::guard('company_user')->user();

        if ($companyUserObj->bg_check_status != 'pending' && $companyUserObj->bg_check_document_id != null) {
            flash('Background check process already done.')->warning();
            return back();
        }

        $data = [
            'admin_page_title' => 'Submit Background Check/Credit Check',
            'states' => State::order()->pluck('name', 'short_name'),
            'company_user' => $companyUserObj,
            'stay_years' => [
                '1' => '1+ Years',
                '2' => '2+ Years',
                '3' => '3+ Years',
                '4' => '4+ Years',
                '5' => '5+ Years',
                '6' => '6+ Years',
                '7' => '7+ Years',
            ],
        ];

        return view($this->view_base . 'index', $data);
    }

    private function savePsqData($step, $requestArr) {

        $company_user_id = Auth::guard('company_user')->user()->id;

        $psq_session = PsqSession::firstOrCreate(['company_user_id' => $company_user_id]);
        $content = json_decode($psq_session->content, true);

        $content[$step] = $requestArr;
        $content = json_encode($content);
        $psq_session->content = $content;
        $psq_session->save();
    }

    public function postStep1(Request $request) {
        $validation_arr = [
            'step1_agree' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }
        $requestArr = $request->all();
        $this->savePsqData('step1', $requestArr);
        return ['status' => 1];
    }

    public function postStep2(Request $request) {
        $validation_arr = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'telephone' => 'required',
            'birth_date' => 'required',
            'ssn' => 'required',
            'driver_license' => 'required',
            //
            'address_line_1' => 'required',
            //'address_line_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'message' => 'Please fill all required fields']);
        }

        $requestArr = $request->all();

        $birth_date = Carbon::parse($requestArr['birth_date']);
        $today_date = now();

        $diff = $today_date->diffInMonths($birth_date);

        if ($diff < 216) { // 216 = 18 years
            return response()->json(['status' => '0', 'You should be above 18+ years']);
        }
        $requestArr['birth_date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['birth_date'])->format(env('DB_DATE_FORMAT'));

        // Upload Driver License
        if ($request->hasFile('driver_license')) {
            $mediaObj = \App\Models\Custom::uploadFile($request->file('driver_license'),'media',[],true);
            $requestArr['driver_license_id'] = $mediaObj['mediaObj']->id;
        }

        $this->savePsqData('step2', $requestArr);
        return ['status' => 1];
    }

    public function postStep3(Request $request) {
        $validation_arr = [
            'convicted_in_fraud' => 'required',
            'convicted_in_felony' => 'required',
            'bankruptcy' => 'required',
            'other_business_name' => 'required',
                //'changed_name' => 'required',
        ];

        if ($request->has('other_business_name') && $request->get('other_business_name') == 'Yes') {
            $validation_arr['business_name_list'] = 'required';
        }

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }

        $this->savePsqData('step3', $request->all());
        return ['status' => 1];
    }

    public function postStep4(Request $request) {
        $validation_arr = [
            'step4_signature' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }

        $this->savePsqData('step4', $request->all());
        return ['status' => 1];
    }

    public function postStep5(Request $request) {
        $validation_arr = [
            'step5_signature' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_arr);

        if ($validator->fails()) {
            return response()->json(['status' => '0', 'Please fill all required fields']);
        }


        $this->savePsqData('step5', $request->all());
        return ['status' => 1];
    }

    public function postStep6(Request $request) {
        $this->savePsqData('step6', $request->all());

        $company_user_id = Auth::guard('company_user')->user()->id;
        $companyUserObj = Auth::guard('company_user')->user();

        $psq_session_item = PsqSession::where('company_user_id', $company_user_id)->firstOrFail();
        $content = json_decode($psq_session_item->content, true);

        $requestArr = [
            // Step 2
            'first_name' => $content['step2']['first_name'],
            'middle_name' => $content['step2']['middle_name'],
            'last_name' => $content['step2']['last_name'],
            'email' => $content['step2']['email'],
            'telephone' => $content['step2']['telephone'],
            'gender' => $content['step2']['gender'],
            'birth_date' => $content['step2']['birth_date'],
            'ssn' => $content['step2']['ssn'],
            'driver_license_id' => isset($content['step2']['driver_license_id']) ? $content['step2']['driver_license_id'] : null,
            //
            'address_line_1' => $content['step2']['address_line_1'],
            'address_line_2' => $content['step2']['address_line_2'],
            'city' => $content['step2']['city'],
            'state' => $content['step2']['state'],
            'zipcode' => $content['step2']['zipcode'],
            // Step 3
            'convicted_in_fraud' => $content['step3']['convicted_in_fraud'],
            'convicted_in_felony' => $content['step3']['convicted_in_fraud'],
            'bankruptcy' => $content['step3']['bankruptcy'],
            'other_business_name' => $content['step3']['other_business_name'],
            'business_name_list' => $content['step3']['business_name_list'],
            //'changed_name' => $content['step3']['changed_name'],
            //'changed_name_list' => $content['step3']['changed_name_list'],
            // Step 6
            'signature' => $content['step6']['signature'],
            'company_user_id' => $company_user_id,
            'company_id' => $companyUserObj->company_id,
            'ip_address' => $request->ip(),
            'ssn' => substr($content['step2']['ssn'], -4),
        ];
        //dd($requestArr);
        // Add BG Check Submit Code [Start]
        $company_user_pre_screen_questions = CompanyOwnerPreScreenQuestion::firstOrcreate(['company_user_id' => $company_user_id]);
        $company_user_pre_screen_questions->update($requestArr);


        /* Application pdf generate start */
        $company_user_pre_screen_question_item = CompanyOwnerPreScreenQuestion::with(['driver_license'])->where('company_user_id', $company_user_id)->first();
        $data['item'] = $company_user_pre_screen_question_item;


        $this->generateBGCheckPDF($company_user_id);

        /* tazworks api call start */
        $data = [
            'company_user_pre_screen_questions' => $company_user_pre_screen_questions,
            'ssn' => $content['step2']['ssn'],
                //'addressArr' => json_decode($company_user_pre_screen_questions->address, true),
        ];

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        if (!is_null($company_user_pre_screen_questions->driver_license)) {
              $driver_license_file_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $company_user_pre_screen_questions->driver_license->file_name);                          
            //$driver_license_file_path = asset('uploads/media/' . $company_user_pre_screen_questions->driver_license->file_name);
            //$driver_license_file_path = rtrim(url('/')) . '/uploads/media/' . $company_user_pre_screen_questions->driver_license->file_name;
            $img = file_get_contents($driver_license_file_path, false, stream_context_create($arrContextOptions));
            // Encode the image string data into base64 
            if ($img != '') {
                $data['driver_license_file_name'] = $company_user_pre_screen_questions->driver_license->file_name;
                $data['driver_license_data'] = base64_encode($img);
            }
        }


        if (!is_null($company_user_pre_screen_questions->bg_check_pdf)) {            
            $bg_check_pdf_file_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $company_user_pre_screen_questions->bg_check_pdf->file_name);
            //$bg_check_pdf_file_path = asset('/uploads/media/' . $company_user_pre_screen_questions->bg_check_pdf->file_name);
            //$bg_check_pdf_file_path = rtrim(url('/'), '/index.php') . '/uploads/media/' . $company_user_pre_screen_questions->bg_check_pdf->file_name;
            $img = file_get_contents($bg_check_pdf_file_path, false, stream_context_create($arrContextOptions));
            //$img = @file_get_contents($bg_check_pdf_file_path);
            // Encode the image string data into base64 
            if ($img != '') {
                $data['bg_check_pdf_file_name'] = $company_user_pre_screen_questions->bg_check_pdf->file_name;
                $data['bg_check_pdf_data'] = base64_encode($img);
            }
        }


        $data['API_USER_ID'] = env(env('API_MODE') . '_USER_ID');
        $data['API_PASSWORD'] = env(env('API_MODE') . '_PASSWORD');
        $data['API_PACKAGE'] = env(env('API_MODE') . '_PACKAGE');
        $api_link = env(env('API_MODE') . '_API_LINK');


        $str = view($this->view_base . 'bg_check_generate_xml', $data)->render();
        //echo $str; exit;

        $companyUserObj = Auth::guard('company_user')->user();

        $apiResponseArr = Custom::tazworksapi($str, $api_link);

        if (isset($apiResponseArr['BackgroundReportPackage']['OrderId']) && $apiResponseArr['BackgroundReportPackage']['OrderId'] != '') {
            //dd($apiResponseArr);
            $companyUserObj->bg_check_order_id = $apiResponseArr['BackgroundReportPackage']['OrderId'];
            $companyUserObj->bg_check_status = $apiResponseArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'];
            $companyUserObj->save();
        } else {
            $companyUserObj->bg_check_order_id = null;
            $companyUserObj->bg_check_status = null;
            $companyUserObj->save();

            //$company_user_pre_screen_questions->delete();
            flash($apiResponseArr['BackgroundReportPackage']['ErrorReport']['ErrorDescription'])->error();
            return back();
        }
        /* tazworks api call end */


        /* check all owner background check submittal process start */
        Custom::owner_background_check_submittal($companyUserObj->company_id, $companyUserObj->company->number_of_owners);
        /* check all owner background check submittal process end */
        $psq_session_item->delete();


        /* Company User submitted background check process mail to Company User */
        $web_settings = $this->web_settings;
        $companyObj = Company::find($companyUserObj->company_id);
        $company_mail_id = "102"; /* Mail title: Company User Submitted Background Check Process */
        $companyReplaceArr = [
            'first_name' => $companyUserObj->first_name,
            'last_name' => $companyUserObj->last_name,
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
            'request_generate_link' => $companyUserObj->email,
            'date' => $companyUserObj->created_at->format(env('DATE_FORMAT')),
            'url' => url('dashboard'),
            'email_footer' => $companyUserObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => url('dashboard')
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $companyReplaceArr);
        Mail::to($companyUserObj->email)->send(new CompanyMail($company_mail_id, $companyReplaceArr));

        flash('Background Check Request has been submitted successfully.')->success();
        return redirect('dashboard');
        // Add BG Check Submit Code [End]
    }

    private function generateBGCheckPDF($company_user_id) {
        /* Application pdf generate start */
        $company_user_pre_screen_question_item = CompanyOwnerPreScreenQuestion::where('company_user_id', $company_user_id)->first();
        $data['item'] = $company_user_pre_screen_question_item;

        //dd($data);

        $pdf = PDF::loadView('company.pre_screen.pdf', $data);

        $file_name = 'BG-Check-' . $company_user_id . '.pdf';        
        //$file_path = 'uploads/media/' . $file_name;
        $file_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $file_name);
        $pdf->save($file_path);


        $media_item = \App\Models\Media::firstOrCreate([
                    'file_name' => $file_name,
                    'original_file_name' => $file_name,
                    'file_type' => 'application/pdf',
                    'file_extension' => 'pdf',
        ]);

        $company_user_pre_screen_question_item->bg_check_pdf_id = $media_item->id;


        // Pre Screen Question File

        $pdf = PDF::loadView('company.pre_screen.pre_screen_pdf', $data);

        $file_name = 'Pre-Screen-Question-' . $company_user_id . '.pdf';
        $file_path = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'media'. DIRECTORY_SEPARATOR . $file_name);
        //$file_path = 'uploads/media/' . $file_name;
        $pdf->save($file_path);


        $pre_media_item = \App\Models\Media::firstOrCreate([
                    'file_name' => $file_name,
                    'original_file_name' => $file_name,
                    'file_type' => 'application/pdf',
                    'file_extension' => 'pdf',
        ]);

        $company_user_pre_screen_question_item->pre_screen_question_file_id = $pre_media_item->id;

        $company_user_pre_screen_question_item->save();

        //dd($company_user_pre_screen_question_item->toArray());

        return $file_name;
    }

    public function postPreScreenQuestions(Request $request) {
        $companyUserObj = Auth::guard('company_user')->user();

        if ($companyUserObj->bg_check_status != 'pending') {
            flash('Background check process already done.')->warning();
            return back();
        }

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'telephone' => 'required',
                    'birth_date' => 'required',
                    'address_line_1' => 'required',
                    //'address_line_2' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'zipcode' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            //dd($requestArr);

            $birth_date = Carbon::parse($requestArr['birth_date']);
            $today_date = now();

            $diff = $today_date->diffInMonths($birth_date);

            if ($diff < 216) { // 216 = 18 years
                flash('You should be above 18 year+')->error();
                return back();
            }

            $requestArr['birth_date'] = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $requestArr['birth_date'])->format(env('DB_DATE_FORMAT'));
            $requestArr['company_user_id'] = $companyUserObj->id;
            $requestArr['company_id'] = $companyUserObj->company_id;
            $company_user_pre_screen_questions = CompanyOwnerPreScreenQuestion::create($requestArr);

            /* tazworks api call start */
            $data = [
                'company_user_pre_screen_questions' => $company_user_pre_screen_questions,
                'ssn' => $request->get('ssn'),
            ];

            $str = view($this->view_base . 'bg_check_generate_xml', $data)->render();

            //echo $str; exit;

            $apiResponseArr = Custom::tazworksapi($str);

            if (isset($apiResponseArr['BackgroundReportPackage']['OrderId']) && $apiResponseArr['BackgroundReportPackage']['OrderId'] != '') {
                //dd($apiResponseArr);
                $companyUserObj->bg_check_order_id = $apiResponseArr['BackgroundReportPackage']['OrderId'];
                $companyUserObj->bg_check_status = $apiResponseArr['BackgroundReportPackage']['ScreeningStatus']['OrderStatus'];
                $companyUserObj->save();
            } else {
                $company_user_pre_screen_questions->delete();
                flash($apiResponseArr['BackgroundReportPackage']['ErrorReport']['ErrorDescription'])->error();
                return back();
            }
            /* tazworks api call end */


            /* check all owner background check submittal process start */
            Custom::owner_background_check_submittal($companyUserObj->company_id, $companyUserObj->company->number_of_owners);
            /* check all owner background check submittal process end */

            flash('Background Check Request has been submitted successfully.')->success();
            return redirect('dashboard');
        }
    }

}

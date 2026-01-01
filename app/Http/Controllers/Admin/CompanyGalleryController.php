<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Media;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyGallery;
use App\Models\Custom;

class CompanyGalleryController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();

        // common model
        $this->modelObj = new CompanyGallery;
        // View
        $this->view_base = 'admin.company_galleries.';
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $companyModelObj = new Company;
        $rows = $companyModelObj->getPendingCompanyGalleryAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect('admin/companies/company-galleries' . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => 'Company Gallery Photo Requests',
            'module_plural_name' => 'Company Gallery Photo Requests',
            'module_singular_name' => 'Company Gallery Photo Request',
            'rows' => $rows,
            'list_params' => $list_params,
            'url_key' => 'company-galleries',
            'module_urls' => ['list' => 'company-galleries'],
        ];

        return view($this->view_base . 'index', $data);
    }

    public function manage_gallery_requests($company_id) {
        $companyObj = Company::findOrFail($company_id);
        $company_galleries = $this->modelObj->with('media')
                ->where([
                    ['company_id', $company_id],
                    ['status', 'pending']
                ])
                ->order()
                ->get();

        $data = [
            'admin_page_title' => $companyObj->company_name . "'s Gallery Request",
            'module_plural_name' => $companyObj->company_name . "'s Gallery Request",
            'company_galleries' => $company_galleries
        ];

        return view($this->view_base . 'gallery_requests', $data);
    }

    public function change_company_gallery_status(Request $request) {
        $validator = Validator::make($request->all(), [
                    'gallery_id' => 'required',
                    'gallery_status' => 'required',
        ]);

        if (isset($validator) && $validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $galleryObj = $this->modelObj->where('status', 'pending')->find($requestArr['gallery_id']);

            if (!is_null($galleryObj)) {
                $web_settings = $this->web_settings;
                $companyObj = Company::find($galleryObj->company_id);
                $companyUserObj = CompanyUser::where([
                            ['company_id', $galleryObj->company_id],
                            ['company_user_type', 'company_super_admin']
                        ])
                        ->first();

                if ($requestArr['gallery_status'] == 'approved' || $requestArr['gallery_status'] == 'rejected') {
                    $requestArr['status'] = $requestArr['gallery_status'];
                    $galleryObj->update($requestArr);

                    /* Company gallery status change mail to Company */
                    if ($requestArr['gallery_status'] == 'approved') {
                        $mail_id = "114"; /* Mail title: Company Gallery Status Update */
                        $replaceArr = [
                            'company_name' => $companyObj->company_name,
                            'status' => ucfirst($requestArr['gallery_status']),
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
                            'date' => $galleryObj->created_at->format(env('DATE_FORMAT')),
                            'url' => url('company_galleries'),
                            'email_footer' => $companyUserObj->email,
                            'copyright_year' => date('Y'),
                                //'main_service_category' => '',
                        ];
                    } else if ($requestArr['gallery_status'] == 'rejected') {
                        $mail_id = "130"; /* Mail title: Company Gallery Rejected */
                        $replaceArr = [
                            'company_name' => $companyObj->company_name,
                            'reject_reason' => $requestArr['reject_note'],
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
                            'date' => $galleryObj->created_at->format(env('DATE_FORMAT')),
                            'url' => url('company_galleries'),
                            'email_footer' => $companyUserObj->email,
                            'copyright_year' => date('Y'),
                                //'main_service_category' => '',
                        ];
                    }
                } else if ($requestArr['gallery_status'] == 'delete') {
                    $mediaObj = Media::find($galleryObj->media_id);
                    if (!is_null($mediaObj)) {
                        Custom::delete_media($mediaObj);
                    }

                    $galleryObj->delete();

                    /* Company gallery deleted mail to Company */
                    $mail_id = "115"; /* Mail title: */
                    $replaceArr = [
                        'company_name' => $companyObj->company_name,
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
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('company_galleries'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('Y'),
                            //'main_service_category' => '',
                    ];
                }


                $messageArr = [
                    'company_id' => $companyObj->id,
                    'message_type' => 'info',
                    'link' => url('company_galleries')
                ];
                Custom::companyMailMessageCreate($messageArr, $mail_id, $replaceArr);
                $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr AS $mail_item) {
                        //Mail::to('ajay.makwana87@gmail.com')->send(new CompanyMail($company_mail_id, $replaceWithArr));
                        Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceArr));
                    }
                }

                /* Create company page screen shot start */
                Custom::createCompanyPageScreenShot($companyObj);
                /* Create company page screen shot end */

                flash("Gallery status change successfully.")->success();
                return back();
            } else {
                flash("Gallery not found!")->warning();
                return back();
            }
        }
    }

}

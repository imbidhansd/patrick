<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;

use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;

use Auth;
use Validator;
use App\Models\Custom;
use App\Models\Company;
use App\Models\SiteLogo;
use App\Models\CompanyLogo;
use App\Models\PartnerLink;
use App\Models\Artwork;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CompanyPageController extends Controller {

    const FOUNDING_MEMBER = 'Founding Member';
    const OFFICIAL_MEMBER = 'Official Member';
    const RECOMMENDED_COMPANY = 'Recommended Company';
    const CERTIFIED_PRO = 'Certified Pro';
    const DOMAIN_SLUG_TP = 'tp';
    const DOMAIN_SLUG_AAD = 'aad';

    public function __construct() {
        $this->web_settings = Custom::getSettings();
        $this->view_base = 'company.pages.';

        $this->statusArr = ['Approved', 'Final Review', 'Pending Approval', 'Paid Pending'];
    }

    public function member_resources(Request $request) {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);
        $domain_slug = $request->query('domain_slug') ?? self::DOMAIN_SLUG_TP;
        $show_aad_banner = view()->shared('show_aad_banner');

        if ($companyObj->membership_level->paid_members == 'no' || $companyObj->status == 'Expired') {
            $url = url('referral-list/full-listing-more');


            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> <a href="' . $url . '" class="text-primary">Explore Upgrade Options</a>')->info();
            return redirect('dashboard');
        } else if (in_array($companyObj->status, $this->statusArr)) {
            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> You will have access to this page upon approval.')->info();
            return redirect('dashboard');
        }

        if($domain_slug == self::DOMAIN_SLUG_AAD && $show_aad_banner == false)
        {
            flash('You are not authorized to view this page')->info();
            return redirect('dashboard');
        }

        $data = [
            'admin_page_title' => 'Website Banners' . ' for '.Custom::getFullDomain($domain_slug),
            'founding_item_list' => SiteLogo::select(
                                    'site_logos.*',
                                    DB::raw('CASE
                                    WHEN affiliates.member_base_url IS NULL
                                    THEN "' . url("/", ["company_slug" => $companyObj->slug]) . '"
                                    ELSE CONCAT(affiliates.member_base_url, "' . $companyObj->slug . '")
                                    END AS member_url')
                                )
                                ->leftJoin('affiliates', 'site_logos.domain_slug', '=', 'affiliates.domain_abbr')
                                ->where('site_logos.banner_for', self::FOUNDING_MEMBER)
                                ->where('site_logos.domain_slug', $domain_slug)
                                ->active()->order()->get(),
            'official_item_list' => SiteLogo::select(
                                    'site_logos.*',
                                    DB::raw('CASE
                                    WHEN affiliates.member_base_url IS NULL
                                    THEN "' . url("/", ["company_slug" => $companyObj->slug]) . '"
                                    ELSE CONCAT(affiliates.member_base_url, "' . $companyObj->slug . '")
                                    END AS member_url')
                                )
                                ->leftJoin('affiliates', 'site_logos.domain_slug', '=', 'affiliates.domain_abbr')
                                ->where('site_logos.banner_for', self::OFFICIAL_MEMBER)
                                ->where('site_logos.domain_slug', $domain_slug)
                                ->active()->order()->get(),
            'recommended_item_list' => SiteLogo::select(
                                    'site_logos.*',
                                    DB::raw('CASE
                                    WHEN affiliates.member_base_url IS NULL
                                    THEN "' . url("/", ["company_slug" => $companyObj->slug]) . '"
                                    ELSE CONCAT(affiliates.member_base_url, "' . $companyObj->slug . '")
                                    END AS member_url')
                                )
                                ->leftJoin('affiliates', 'site_logos.domain_slug', '=', 'affiliates.domain_abbr')
                                ->where('site_logos.banner_for', self::RECOMMENDED_COMPANY)
                                ->where('site_logos.domain_slug', $domain_slug)
                                ->active()->order()->get(),
            'certifiedpro_list' => SiteLogo::select(
                                    'site_logos.*',
                                    DB::raw('CASE
                                    WHEN affiliates.member_base_url IS NULL
                                    THEN "' . url("/", ["company_slug" => $companyObj->slug]) . '"
                                    ELSE CONCAT(affiliates.member_base_url, "' . $companyObj->slug . '")
                                    END AS member_url')
                                )
                                ->leftJoin('affiliates', 'site_logos.domain_slug', '=', 'affiliates.domain_abbr')
                                ->where('site_logos.banner_for', self::CERTIFIED_PRO)
                                ->where('site_logos.domain_slug', $domain_slug)
                                ->active()->order()->get(),
            'company_logo_item' => CompanyLogo::where('company_id', $companyObj->id)->first()
        ];

        return view($this->view_base . 'member_resources', $data);
    }

    public function social_media_artwork() {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

        if ($companyObj->membership_level->paid_members == 'no' || $companyObj->status == 'Expired') {
            $url = url('referral-list/full-listing-more');

            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> <a href="' . $url . '" class="text-primary">Explore Upgrade Options</a>')->info();
            return redirect('dashboard');
        } else if (in_array($companyObj->status, $this->statusArr)) {
            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> You will have access to this page upon approval.')->info();
            return redirect('dashboard');
        }


        //Founding
        $founding_records = Artwork::where([
                    ['artwork_type', 'social_media'],
                    ['artwork_for', 'Founding Member']
                ])
                ->active()
                ->order()
                ->get();
        $founding_item_list = [];
        if (count($founding_records) > 0) {
            foreach ($founding_records AS $founding_item) {
                if (!is_null($founding_item->social_type)) {
                    $founding_item_list[$founding_item->social_type][] = $founding_item;
                }
            }
        }


        //Official
        $official_records = Artwork::where([
                    ['artwork_type', 'social_media'],
                    ['artwork_for', 'Official Member']
                ])
                ->active()
                ->order()
                ->get();
        $official_item_list = [];
        if (count($official_records) > 0) {
            foreach ($official_records AS $official_item) {
                if (!is_null($official_item->social_type)) {
                    $official_item_list[$official_item->social_type][] = $official_item;
                }
            }
        }


        //Recommended
        $recommended_records = Artwork::where([
                    ['artwork_type', 'social_media'],
                    ['artwork_for', 'Recommended Company']
                ])
                ->active()
                ->order()
                ->get();
        $recommended_item_list = [];
        if (count($recommended_records) > 0) {
            foreach ($recommended_records AS $recommended_item) {
                if (!is_null($recommended_item->social_type)) {
                    $recommended_item_list[$recommended_item->social_type][] = $recommended_item;
                }
            }
        }


        $data = [
            'admin_page_title' => 'Social Media Artwork',
            //Founding
            'founding_item_list' => $founding_item_list,
            //Official
            'official_item_list' => $official_item_list,
            //Recommended
            'recommended_item_list' => $recommended_item_list,
        ];

        //dd($data);

        return view($this->view_base . 'social_media_artwork', $data);
    }

    public function print_ready_artwork() {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

        if ($companyObj->membership_level->paid_members == 'no' || $companyObj->status == 'Expired') {
            $url = url('referral-list/full-listing-more');


            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> <a href="' . $url . '" class="text-primary">Explore Upgrade Options</a>')->info();
            return redirect('dashboard');
        } else if (in_array($companyObj->status, $this->statusArr)) {
            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> You will have access to this page upon approval.')->info();
            return redirect('dashboard');
        }


        //Founding
        $founding_records = Artwork::where([
                    ['artwork_type', 'print_ready'],
                    ['artwork_for', 'Founding Member']
                ])
                ->active()
                ->order()
                ->get();

        //Official
        $official_records = Artwork::where([
                    ['artwork_type', 'print_ready'],
                    ['artwork_for', 'Official Member']
                ])
                ->active()
                ->order()
                ->get();

        //Recommended
        $recommended_records = Artwork::where([
                    ['artwork_type', 'print_ready'],
                    ['artwork_for', 'Recommended Company']
                ])
                ->active()
                ->order()
                ->get();

        $data = [
            'admin_page_title' => 'Print Ready Artwork',
            //Founding
            'founding_records' => $founding_records,
            //Official
            'official_records' => $official_records,
            //Recommended
            'recommended_records' => $recommended_records,
        ];

        return view($this->view_base . 'print_ready_artwork', $data);
    }

    public function set_company_logo_banner(Request $request) {
        $validator = Validator::make($request->all(), [
                    'url' => 'required',
                    'site_logo_id' => 'required',
                        ], [
                    'site_logo_id.required' => 'Kindly select at least one banner'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $companyUserObj = Auth::guard('company_user')->user();
            $requestArr['company_id'] = Auth::guard('company_user')->user()->company_id;
            $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

            // check company set or not
            $companyLogoObj = CompanyLogo::where('company_id', $requestArr['company_id'])->first();
            if (!is_null($companyLogoObj)) {
                $companyLogoObj->update($requestArr);
            } else {
                $requestArr['unique_key'] = Custom::getRandomString(40);
                $companyLogoObj = CompanyLogo::create($requestArr);
            }


            /* Company logo banner updated mail to Company */
            $web_settings = $this->web_settings;
            $company_mail_id = "77"; /* Mail Title: Company Bio Received */
            $replaceWithArr = [
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
                'date' => $companyLogoObj->created_at->format(env('DATE_FORMAT')),
                'url' => url('member-resources'),
                'email_footer' => $companyUserObj->email,
                'copyright_year' => date('Y'),
                    //'main_service_category' => '',
            ];

            $messageArr = [
                'company_id' => $companyObj->id,
                'message_type' => 'info',
                'link' => url('member-resources')
            ];
            Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceWithArr);
            $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
            if (!is_null($mailArr) && count($mailArr) > 0) {
                foreach ($mailArr AS $mail_item) {
                    Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
                }
            }


            /* Company logo banner updated mail to Admin */
            if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
                $admin_mail_id = "78"; /* Mail Title: Company Bio Uploaded - Admin */
                $adminReplaceWithArr = [
                    'company_name' => $companyObj->company_name,
                ];

                Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
            }

            flash("Banner/Logo set successfully.")->success();
            return back();
        }
    }

    public function partner_links() {
        $companyObj = Company::find(Auth::guard('company_user')->user()->company_id);

        if ($companyObj->membership_level->paid_members == 'no' || $companyObj->status == 'Expired') {
            $url = url('referral-list/full-listing-more');


            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> <a href="' . $url . '" class="text-primary">Explore Upgrade Options</a>')->info();
            return redirect('dashboard');
        } else if (in_array($companyObj->status, $this->statusArr)) {
            flash('Oops. This content is for <strong class="text-info">Members</strong> only. <br /> You will have access to this page upon approval.')->info();
            return redirect('dashboard');
        }

        $data = [
            'admin_page_title' => 'Partner Links',
            'partner_links' => PartnerLink::with('media')->active()->order()->get(),
        ];

        return view($this->view_base . 'partner_links', $data);
    }

}

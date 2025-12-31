<?php

namespace App\Models;

use \Image;
use Auth;
use Session;
use Validator;
use ImageOptimizer;
use Webp;
use DB;
use Str;
use Arr;
use App\Mail\Followup\FollowUpMail;
use App\Mail\Company\CompanyCustomMail;
use App\Mail\Company\CompanyMail;
use App\Mail\Company\CompanyMailV1;
use App\Mail\Admin\AdminMail;
use App\Mail\Company\BroadcastMail;
use Illuminate\Support\Facades\Mail;
// Our Models
use App\Models\Setting;
use App\Models\Media;
use App\Models\User;
use App\Models\Lead;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyMessage;
use App\Models\CompanyLead;
use App\Models\CompanyApprovalStatus;
use App\Models\CompanyServiceCategory;
use App\Models\CompanyInvoice;
use App\Models\CompanyInvoiceItem;
use App\Models\CompanyLicensing;
use App\Models\ShoppingCartServiceCategory;
use App\Models\BroadcastEmail;
use App\Models\NewEmail;
use \Carbon\Carbon;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;
use App\Models\KeyIdentifierType;
use function PHPUnit\Framework\returnValueMap;
/**
 * Custom Class.
 *
 * @subpackage custom class
 * @author
 */
class Custom {
    /*     * *********************************************** */
    /*                Misc Functions                  */
    /*     * *********************************************** */
    const DOMAIN_SLUG_TP = 'tp';
    const DOMAIN_SLUG_AAD = 'aad';

    public static function sendHtmlMail($to, $subject, $mail_content, $from = '') {
        $headers = '';
        if ($from != '') {
            $headers .= "From: " . strip_tags($from) . "\r\n";
            $headers .= "Reply-To: " . strip_tags($from) . "\r\n";
        }

        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (mail($to, $subject, $mail_content, $headers)) {
            return 'mail_sent';
        } else {
            return 'error';
        }
    }

    public static function check_captcha($request) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => env('RECAPTCHA_V3_SECRET_KEY'),
            'response' => $request->get('recaptcha'),
        ];

        $options = [
            'http' => [
                'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return json_decode($result);
    }

    /*     * *********************************************** */
    /*                String Functions                */
    /*     * *********************************************** */

    public static function getRandomString($len = 30) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        //$chars = "0123456789";
        $r_str = "";
        for ($i = 0; $i < $len; $i++)
            $r_str .= substr($chars, rand(0, strlen($chars)), 1);

        if (strlen($r_str) != $len) {
            $r_str .= self::getRandomString($len - strlen($r_str));
        }

        return $r_str;
    }

    public static function getSubString($str, $len = 30) {

        if (strlen($str) > $len) {
            return substr($str, 0, $len) . '...';
        } else {
            return $str;
        }
    }

    /*     * *********************************************** */
    /*              Admin/Front Functions             */
    /*     * *********************************************** */

    public static function generateAdminListQuery($params, $query, $all_search_fields, $table_name, $date_field = 'created_at') {

        $search = isset($params['search']) ? $params['search'] : [];
        $searchField = isset($params['search_field']) ? trim($params['search_field']) : '';
        $searchText = isset($params['search_text']) ? trim($params['search_text']) : '';
        $from_date = isset($params['from_date']) ? trim($params['from_date']) : '';
        $to_date = isset($params['to_date']) ? trim($params['to_date']) : '';
        $sortBy = isset($params['sort_by']) ? $params['sort_by'] : '';
        $sortOrd = isset($params['sort_order']) ? $params['sort_order'] : 'DESC';

        // filter query
        if ($searchField != "" && $searchText != "") {
            if ($searchField == "all") {
                $query->where(function ($q) use ($searchText, $all_search_fields) {
                    if (count($all_search_fields) > 0) {
                        array_shift($all_search_fields);

                        $counter = 0;

                        foreach ($all_search_fields as $field) {
                            if ($counter == 0) {
                                $q->where($field, 'like', '%' . $searchText . '%');
                            } else {
                                $q->orWhere($field, 'like', '%' . $searchText . '%');
                            }
                            $counter++;
                        }
                    }
                });
            } else {
                $query->where($searchField, 'LIKE', '%' . $searchText . '%');
            }
        }

        // Search By Dates
        if ($from_date != "") {
            $from_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $from_date)->format(env('DB_DATE_FORMAT'));
            $query->whereDate($table_name . '.' . $date_field, '>=', $from_date);
        }
        if ($to_date != "") {
            $to_date = \Carbon\Carbon::createFromFormat(env('DATE_FORMAT'), $to_date)->format(env('DB_DATE_FORMAT'));
            $query->whereDate($table_name . '.' . $date_field, '<=', $to_date);
        }

        // Special Search
        if (isset($search) && is_array($search) && count($search) > 0) {
            foreach ($search as $search_item_field => $search_item_value) {
                if ($search_item_value != '') {
                    $query->where($search_item_field, $search_item_value);
                }
            }
        }

        // sort query
        if ($sortBy != "" && $sortOrd != "") {
            $query->orderBy($sortBy, $sortOrd);
        } else {
            $query->order();
        }


        return $query;
    }

    public static function getSortingLink($link, $heading, $field, $curSortBy = '', $curSortOrder = 'asc', $search_field = '', $search_val = '', $extra_params = '') {
        $qs = '?';
        if (strpos($link, '?') != false) {
            $qs = '&';
        }
        $caret = '<i class="fa fa-angle-up"></i>';
        if ($field != $curSortBy) {
            $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
            $caret = '';
        } elseif ($field == $curSortBy) {
            if ($curSortOrder == "asc") {
                $link .= $qs . 'sortBy=' . $field . '&sortOrd=desc';
            } elseif ($curSortOrder == "desc") {
                $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
                $caret = '<i class="fa fa-angle-down"></i>';
            } else {
                $link .= $qs . 'sortBy=' . $field . '&sortOrd=asc';
            }
        }
        if ($search_field != "" && $search_val != "") {
            $link .= '&search_field=' . $search_field . "&search_text=" . $search_val;
        }
        if ($extra_params != "") {
            $link .= "&" . $extra_params;
        }
        return '<a href="' . $link . '">' . $heading . ' ' . $caret . '</a>';
    }

    public static function getListParams($request, $extras = []) {

        $list_params = array(
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'search' => $request->get('search'),
            'search_field' => $request->get('search_field'),
            'mile_range' => $request->get('mile_range'),
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sortBy'),
            'sort_order' => $request->get('sortOrd'),
            'record_per_page' => $request->has('record_per_page') && is_numeric($request->get('record_per_page')) ? $request->get('record_per_page') : env('APP_RECORDS_PER_PAGE', 10),
        );

        if (count($extras)) {
            foreach ($extras as $item) {
                if ($request->has($item)) {
                    $list_params[$item] = $request->get($item);
                }
            }
        }

        return $list_params;
    }

    public static function makeUrlQueryString($list_params) {
        $qs = '?';
        if (is_array($list_params) && count($list_params) > 0) {
            $qs .= http_build_query($list_params);
            /* foreach ($list_params as $key => $val) {
              if ($val != '') {
              $qs .= $key . '=' . $val . '&';
              }
              } */
            return rtrim($qs, '&');
        } else {
            return '';
        }
    }

    public static function showDateTime($datetime, $showFormat, $currentFormat = 'Y-m-d H:i:s') {
        if ($datetime != '' && $datetime != null) {
            return Carbon::createFromFormat($currentFormat, $datetime)->format($showFormat);
        }
        return '';
    }

    public static function getSettings($setting_type = 'web') {
        //$settings = Setting::where('setting_type', '=', $setting_type)->get();
        $settings = Setting::get();
        $settingArr = [];
        if (count($settings) > 0) {
            foreach ($settings as $item) {
                $settingArr[$item->name] = $item->value;
            }
        }
        return $settingArr;
    }

    public static function getModuleFlashMessages($module_display_name) {
        return [
            'add' => $module_display_name . " has been added successfully!",
            'update' => $module_display_name . " has been updated successfully!",
            'delete' => $module_display_name . " has been deleted successfully!",
            'delete_error' => $module_display_name . " can not be deleted!"
        ];
    }

    public static function getModuleUrls($url_key = '', $common_module = true) {
        if ($url_key != '') {
            return [
                'url_key' => $url_key,
                'url_key_singular' => Str::singular($url_key),
                'list' => route($url_key . '.index'),
                'add' => route($url_key . '.create'),
                'store' => route($url_key . '.store'),
                'edit' => $url_key . '.edit',
                'update' => $url_key . '.update',
                'delete' => $url_key . '.destroy',
                'reorder' => url("admin/" . $url_key . "/re-order"),
                'update_status' => $common_module ? 'update-status' : $url_key . '-update-status',
                'form_file' => 'admin.' . $url_key . '.form',
            ];
        } else {
            return [];
        }
    }

    /*     * *********************************************** */
    /*              File/Media Functions              */
    /*     * *********************************************** */

    public static function makeDir($path) {

        if (!file_exists($path)) {
            mkdir($path);
        }

        /* if (chmod($path, 0777)) {
          // more code
          //chmod($path, 0777);
          } */
    }

    public static function uploadFile($file, $prefix = 'media', $validExtensions = [], $isSecure = false)
    {
        $file_mime_type = $file->getClientMimeType();
        $file_extension = $file->getClientOriginalExtension();
        $original_file_name = $file->getClientOriginalName();

        if (is_array($validExtensions) && count($validExtensions) > 0) {
            if (!in_array($file_extension, $validExtensions)) {
                return false;
            }
        }

        $safeName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $original_file_name);
        $filename = $prefix . '_' . rand(1000000, 9999999) . '-' . $safeName;
        $webp_filename = $filename . '.webp';

        // Determine target path
        if ($isSecure) {
            $uploadPath = storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $prefix);
        } else {
            $uploadPath = public_path('uploads' . DIRECTORY_SEPARATOR . $prefix);
        }

        // Make sure the directory exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fullpath = $uploadPath . DIRECTORY_SEPARATOR . $filename;

        // Move the uploaded file
        $file->move($uploadPath, $filename);

        $mediaObj = Media::create([
            'file_name' => $filename,
            'original_file_name' => $original_file_name,
            'file_type' => $file_mime_type,
            'file_extension' => $file_extension,
        ]);

        // Image optimization and thumbnail logic
        $imgExtensionArry = ['jpg', 'jpeg', 'png', 'bmp', 'tiff'];

        if (in_array(strtolower($file_extension), $imgExtensionArry)) {
            ImageOptimizer::optimize($fullpath, $fullpath);
            $image_obj = Image::make($fullpath);

            if (env('FIT_THUMBS') != '') {
                self::createThumbnails($fullpath, 'fit_thumbs', $filename, env('FIT_THUMBS'));
            }
            if (env('HEIGHT_THUMBS') != '') {
                self::createThumbnails($fullpath, 'height_thumbs', $filename, env('HEIGHT_THUMBS'));
            }
            if (env('WIDTH_THUMBS') != '') {
                self::createThumbnails($fullpath, 'width_thumbs', $filename, env('WIDTH_THUMBS'));
            }
        }

        return [
            'filename' => $filename,
            'mediaObj' => $mediaObj,
            'is_secure' => $isSecure,
            'fullpath' => $fullpath,
        ];
    }


    public static function createThumbnails($fullpath, $folder_name, $filename, $thumbSizes) {
        $sizes = explode(',', $thumbSizes);
        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $folder_name . DIRECTORY_SEPARATOR;

        foreach ($sizes as $size) {

            $image_obj = Image::make($fullpath);

            $temp = explode('x', $size);
            $tempFilename = $filename;
            $thumbFile = $uploadPath . $size . DIRECTORY_SEPARATOR . $tempFilename;

            if ($folder_name == 'fit_thumbs') {
                $w = isset($temp[0]) ? $temp[0] : 100;
                $h = isset($temp[1]) ? $temp[1] : 100;
                $image_obj->fit($w, $h)->save($thumbFile);
                ImageOptimizer::optimize($thumbFile, $thumbFile);
            } elseif ($folder_name == 'height_thumbs') {
                $h = isset($size) ? $size : 100;
                $image_obj->heighten($h, function ($constraint) {
                    $constraint->upsize();
                })->save($thumbFile);
                ImageOptimizer::optimize($thumbFile, $thumbFile);
            } elseif ($folder_name == 'width_thumbs') {
                $w = isset($size) ? $size : 100;
                $image_obj->widen($w, function ($constraint) {
                    $constraint->upsize();
                })->save($thumbFile);
                ImageOptimizer::optimize($thumbFile, $thumbFile);
            }
            $image_obj->destroy();
        }
    }

    public static function removeMedia($filename) {
        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'media';
        $filepath = $uploadPath . DIRECTORY_SEPARATOR . $filename;
        $optimized_filepath = $uploadPath . DIRECTORY_SEPARATOR . 'optimized' . DIRECTORY_SEPARATOR . $filename;

        if (is_file($filepath)) {
            unlink($filepath);
        }
        if (is_file($optimized_filepath)) {
            unlink($optimized_filepath);
        }

        if (env('FIT_THUMBS') != '') {
            self::removeThumb('fit_thumbs', $filename, env('FIT_THUMBS'));
        }
        if (env('HEIGHT_THUMBS') != '') {
            self::removeThumb('height_thumbs', $filename, env('HEIGHT_THUMBS'));
        }
        if (env('WIDTH_THUMBS') != '') {
            self::removeThumb('width_thumbs', $filename, env('WIDTH_THUMBS'));
        }
    }

    public static function removeThumb($folder_name, $filename, $thumbSizes) {
        $sizes = explode(',', $thumbSizes);
        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $folder_name . DIRECTORY_SEPARATOR;

        foreach ($sizes as $size) {
            $thumbFile = $uploadPath . $size . DIRECTORY_SEPARATOR . $filename;

            if (is_file($thumbFile)) {
                unlink($thumbFile);
            }
        }
    }

    public static function show_file_fa_icon($mediaObj, $class) {

    }

    public static function getCommonData() {
        return [
            'web_settings' => self::getSettings(),
            'current_package' => Auth::check() ? UserPackage::getCurrentPackage(Auth::user()) : [],
        ];
    }

    public static function checkForPermission($url_key, $item = null) {

        if (Auth::user()->role->name == 'Super Admin') {
            return true;
        }

        $module_with_permissions = config('module_with_permissions');
        $message = 'You dont have enough rights to access that page';

        if (!is_null($item) && !Auth::user()->can($url_key . '.' . $item)) {
            flash($message)->error();
            return false;
        } elseif (is_null($item) && !Auth::user()->hasAnyPermission($module_with_permissions[$url_key])) {
            flash($message)->error();
            return false;
        }

        return true;
    }

    public static function getActionArr($url_key) {

        $action_array = [];
        if (Auth::user()->hasAnyPermission($url_key . '.' . 'edit')) {
            $action_array['active'] = 'Active';
            $action_array['inactive'] = 'Inctive';
        }
        if (Auth::user()->hasAnyPermission($url_key . '.' . 'delete')) {
            $action_array['delete'] = 'Delete';
        }
        return $action_array;
    }

    /* Zipcode API [Start] */

    public static function getZipCodeRange($zipcode, $mile_range) {
        $APIkey = env('ZIPCODE_API_KEY');
        $miles = $mile_range;
        $zip = $zipcode;


        $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/radius.json/' . $zip . '/' . $miles . '/mile');
        if ($json != '') {
            $zipcodeArr = json_decode($json);
            $zipCodes = json_decode(json_encode($zipcodeArr), True)['zip_codes'];
            return $zipCodes;
        } else {
            return [];
        }
    }

    public static function getZipcodeDetail($zipcode) {
        $APIkey = env('ZIPCODE_API_KEY');
        $zip = $zipcode;

        $json = @file_get_contents('https://www.zipcodeapi.com/rest/' . $APIkey . '/info.json/' . $zip . '/radians');
        if ($json != '') {
            $zipcodeArr = json_decode($json);
            $zipCodes = json_decode(json_encode($zipcodeArr), True);
            return $zipCodes;
        } else {
            return [];
        }
    }

    /* Zipcode API [End] */

    public static function company_service_category_list($company_id) {
        $company_service_category_list = $removed_company_service_category_list = [];
        $company_service_categories = CompanyServiceCategory::where('company_id', $company_id)->get();

        if (count($company_service_categories) > 0) {
            foreach ($company_service_categories as $company_service_category_item) {
                if ($company_service_category_item->status == 'active') {
                    $company_service_category_list[$company_service_category_item->service_category_type_id][$company_service_category_item->main_category_id][] = $company_service_category_item->service_category_id;
                } else {
                    $removed_company_service_category_list[$company_service_category_item->service_category_type_id][$company_service_category_item->main_category_id][] = $company_service_category_item->service_category_id;
                }
            }
        }

        return [
            'company_service_category_list' => $company_service_category_list,
            'removed_company_service_category_list' => $removed_company_service_category_list
        ];
    }

    public static function custom_update_service_category($requestArr) {
        $validator = Validator::make($requestArr, [
                    'company_id' => 'required',
                    'item_id' => 'required',
                    'item_type' => 'required',
                    'item_category_type' => 'required',
        ]);

        if ($validator->fails()) {
            return false;
        } else {
            if ($requestArr['item_type'] == 'main_category') {
                if ($requestArr['item_process'] == 'add_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['main_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'inactive']
                    ])->update([
                        'status' => 'active'
                    ]);
                } else if ($requestArr['item_process'] == 'remove_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['main_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'active']
                    ])->update([
                        'status' => 'inactive'
                    ]);
                } else if ($requestArr['item_process'] == 'delete_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['main_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'inactive']
                    ])->delete();
                }
            } else {
                if ($requestArr['item_process'] == 'add_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['service_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'inactive']
                    ])->update([
                        'status' => 'active'
                    ]);
                } else if ($requestArr['item_process'] == 'remove_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['service_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'active']
                    ])->update([
                        'status' => 'inactive'
                    ]);
                } else if ($requestArr['item_process'] == 'delete_item') {
                    CompanyServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['service_category_id', $requestArr['item_id']],
                        ['service_category_type_id', $requestArr['item_category_type']],
                        ['status', 'inactive']
                    ])->delete();
                }
            }

            return true;
        }
    }

    public static function date_formats($date, $current_format, $required_format) {
        return \Carbon\Carbon::createFromFormat($current_format, $date)->format($required_format);
    }

    /* Company Invoice Generate Start */

    public static function generateFirstInvoice($requestArr, $shopping_cart_content) {
        $invoice_for = "";
        if ($shopping_cart_content->membership_type == 'annual_price') {
            $invoice_for = "Company Listing/Digital Products Annual Listing";
        } else if ($shopping_cart_content->membership_type == 'monthly_price') {
            $invoice_for = "Company Listing/Digital Products Monthly Listing";
        } else if ($shopping_cart_content->membership_type == 'ppl_price') {
            $invoice_for = "Company Listing/Digital Products Pay-Per Lead Listing";
        }

        $invoice_date = now()->format(env('DATE_FORMAT'));
        $invoice_id = CompanyInvoice::getOrderNumber();

        /* Company Invoice create */
        $company_invoice_insert_arr = [
            'company_id' => $requestArr['company_id'],
            'invoice_type' => 'One Time Setup Fee & Prescreen/Background Check Fees',
            'payment_type' => (($requestArr['payment_option'] == 'credit_card') ? 'credit_card' : 'check'),
            'invoice_date' => $invoice_date,
            'invoice_id' => $invoice_id,
            'invoice_for' => $invoice_for,
            'final_amount' => $shopping_cart_content->setup_fee,
            'invoice_paid_date' => (($requestArr['payment_option'] == 'credit_card') ? now()->format(env('DATE_FORMAT')) : null),
            'status' => (($requestArr['payment_option'] == 'credit_card') ? 'paid' : 'pending')
        ];


        if (isset($requestArr['ship_address_id']) && $requestArr['ship_address_id'] > 0) {
            $company_invoice_insert_arr['ship_address_id'] = $requestArr['ship_address_id'];
        }
        if (isset($requestArr['bill_address_id']) && $requestArr['bill_address_id'] > 0) {
            $company_invoice_insert_arr['bill_address_id'] = $requestArr['bill_address_id'];
        }


        /* if (isset($shopping_cart_content->promotional_code)) {
          $bg_pre_screen_fee = $shopping_cart_content->owner_fee;
          } else {
          $bg_pre_screen_fee = $shopping_cart_content->first_owner_fee;
          if ($shopping_cart_content->number_of_owners > 1) {
          $bg_pre_screen_fee += $shopping_cart_content->other_owner_fee;
          }
          } */



        $company_invoice = CompanyInvoice::create($company_invoice_insert_arr);
        $company_invoice_item_arr = [
            [
                'company_invoice_id' => $company_invoice->id,
                'title' => 'One Time Setup Fee',
                'description' => 'One Time Setup Fee',
                'amount' => $shopping_cart_content->onetime_setup_fee,
                'qty' => '1',
                'total' => $shopping_cart_content->onetime_setup_fee
            ],
                /* [
                  'company_invoice_id' => $company_invoice->id,
                  'title' => 'Pre-screen/Background/Credit Check Fees (' . $shopping_cart_content->number_of_owners . ' Per Owner)',
                  'description' => 'Pre-screen/Background/Credit Check Fees (' . $shopping_cart_content->number_of_owners . ' Per Owner)',
                  'amount' => $bg_pre_screen_fee,
                  'qty' => '1',
                  'total' => $bg_pre_screen_fee
                  ] */
        ];

        $bg_pre_screen_fee = $shopping_cart_content->first_owner_fee;
        if ($shopping_cart_content->number_of_owners > 1) {
            $other_owners = ($shopping_cart_content->number_of_owners - 1);
            $bg_pre_screen_fee += ($shopping_cart_content->other_owner_fee * $other_owners);
        }

        $ary_count = count($company_invoice_item_arr);
        //$bg_pre_screen_fee = $shopping_cart_content->first_owner_fee;
        if ($shopping_cart_content->number_of_owners > 1) {
            $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice->id;
            $company_invoice_item_arr[$ary_count]['title'] = 'Pre-screen/Background/Credit Check Fees (1st Owner)';
            $company_invoice_item_arr[$ary_count]['description'] = 'Pre-screen/Background/Credit Check Fees (1st Owner)';
            $company_invoice_item_arr[$ary_count]['amount'] = $shopping_cart_content->first_owner_fee;
            $company_invoice_item_arr[$ary_count]['qty'] = 1;
            $company_invoice_item_arr[$ary_count]['total'] = $shopping_cart_content->first_owner_fee;
            $ary_count++;


            $other_owners = ($shopping_cart_content->number_of_owners - 1);
            $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice->id;
            $company_invoice_item_arr[$ary_count]['title'] = 'Pre-screen/Background/Credit Check Fees (Other Owners)';
            $company_invoice_item_arr[$ary_count]['description'] = 'Pre-screen/Background/Credit Check Fees (Other Owners)';
            $company_invoice_item_arr[$ary_count]['amount'] = $shopping_cart_content->other_owner_fee;
            $company_invoice_item_arr[$ary_count]['qty'] = $other_owners;
            $company_invoice_item_arr[$ary_count]['total'] = ($shopping_cart_content->other_owner_fee * $other_owners);
        } else {
            $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice->id;
            $company_invoice_item_arr[$ary_count]['title'] = 'Pre-screen/Background/Credit Check Fees (' . $shopping_cart_content->number_of_owners . ' Per Owner)';
            $company_invoice_item_arr[$ary_count]['description'] = 'Pre-screen/Background/Credit Check Fees (' . $shopping_cart_content->number_of_owners . ' Per Owner)';
            $company_invoice_item_arr[$ary_count]['amount'] = $bg_pre_screen_fee;
            $company_invoice_item_arr[$ary_count]['qty'] = 1;
            $company_invoice_item_arr[$ary_count]['total'] = $bg_pre_screen_fee;
        }


        CompanyInvoiceItem::insert($company_invoice_item_arr);

        return $company_invoice;
    }

    public static function generateSecondInvoice($requestArr, $shopping_cart_content) {
        $companyObj = Company::find($requestArr['company_id']);
        $invoice_for1 = "";
        if ($shopping_cart_content->membership_type == 'annual_price') {
            $invoice_for1 = "Referral List Membership/Products Annual Listing";
        } else if ($shopping_cart_content->membership_type == 'monthly_price') {
            $invoice_for1 = "Referral List Membership/Products Monthly Listing";
        } else if ($shopping_cart_content->membership_type == 'ppl_price') {
            $invoice_for1 = "Referral List Membership/Products Pay-Per Lead Listing";
        }

        $invoice_date = now()->format(env('DATE_FORMAT'));
        $invoice_id = CompanyInvoice::getOrderNumber();
        $category_listing_desc = [];

        $service_category_type = ['main', 'sub', 'extra'];

        for ($i = 0; $i < count($service_category_type); $i++) {
            $type = $service_category_type[$i];
            $category_listing_data = ShoppingCartServiceCategory::where([
                        ['company_id', $requestArr['company_id']],
                        ['category_type', $type]
                    ])
                    ->orderBy('service_category_type_id', 'ASC')
                    ->orderBy('top_level_category_id', 'ASC')
                    ->orderBy('main_category_id', 'ASC')
                    ->get();

            if (count($category_listing_data) > 0) {
                $service_category_arr = [];

                $main_category = $category_type = "";
                foreach ($category_listing_data as $category_item) {
                    if ($shopping_cart_content->membership_level_id == 7) {
                        $category_listing_desc[$i][$type . '_price'] = null;
                    } else {
                        if (($shopping_cart_content->membership_type == 'annual_price' || $shopping_cart_content->membership_type == 'monthly_price') && ($main_category != $category_item->main_category_id || $category_type != $category_item->service_category_type_id)) {

                            if (isset($category_listing_desc[$i][$type . '_price'])) {
                                $category_listing_desc[$i][$type . '_price'] += $category_item->fee;
                            } else {
                                $category_listing_desc[$i][$type . '_price'] = $category_item->fee;
                            }
                        } else if ($shopping_cart_content->membership_type == 'ppl_price') {
                            $category_listing_desc[$i][$type . '_price'] = null;
                            /* if (isset($category_listing_desc[$i][$type . '_price'])) {
                              $category_listing_desc[$i][$type . '_price'] += $category_item->fee;
                              } else {
                              $category_listing_desc[$i][$type . '_price'] = $category_item->fee;
                              } */
                        }
                    }


                    $category_type = $category_item->service_category_type_id;
                    $service_category = $category_item->service_category_id;
                    $main_category = $category_item->main_category_id;

                    $service_category_arr[$category_type][$main_category][] = $service_category;
                }

                //dd($category_listing_desc);
                $data['service_category_arr'] = $service_category_arr;

                /* $review = view('company.profile.upgrade._invoice_category_item', $data)->render();

                  echo $review;
                  exit; */

                $category_listing_desc[$i][$type] = view('company.profile.upgrade._invoice_category_item', $data)->render();
            }
        }

        //dd($category_listing_desc);

        $product_price = 0;
        if (isset($shopping_cart_content->suggested_products)) {
            foreach ($shopping_cart_content->suggested_products as $product_item) {
                $product_price += $product_item->price;
            }
        }

        /* company Invoice item create */
        /* if ($companyObj->membership_level_id == '6') {
          $invoice_type = 'PPL Referral List';
          } else {
          $invoice_type = 'Referral List';
          } */

        $company_invoice_insert_arr = [
            'company_id' => $requestArr['company_id'],
            'invoice_type' => 'Referral List',
            'invoice_date' => $invoice_date,
            'invoice_id' => $invoice_id,
            'invoice_for' => $invoice_for1,
            'final_amount' => $shopping_cart_content->total_service_fees + $shopping_cart_content->membership_fee + $product_price,
            'status' => 'waiting'
        ];

        $company_invoice2 = CompanyInvoice::create($company_invoice_insert_arr);
        $company_invoice_item_arr = [
            [
                'company_invoice_id' => $company_invoice2->id,
                'title' => 'Annual Membership/Endorsment Fee',
                'description' => 'Annual Membership/Endorsment Fee',
                'amount' => $shopping_cart_content->membership_fee,
                'qty' => '1',
                'total' => $shopping_cart_content->membership_fee
            ]
        ];

        if (count($category_listing_desc) > 0) {
            $ary_count = count($company_invoice_item_arr);
            foreach ($category_listing_desc as $category_listing_desc_item) {
                $arr_key = array_keys($category_listing_desc_item);

                $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice2->id;
                $company_invoice_item_arr[$ary_count]['amount'] = $category_listing_desc_item[$arr_key[0]];
                $company_invoice_item_arr[$ary_count]['qty'] = '1';
                $company_invoice_item_arr[$ary_count]['total'] = $category_listing_desc_item[$arr_key[0]];
                $company_invoice_item_arr[$ary_count]['description'] = $category_listing_desc_item[$arr_key[1]];

                if ($arr_key[1] == 'main') {
                    $company_invoice_item_arr[$ary_count]['title'] = 'Main Category Listing';
                } else if ($arr_key[1] == 'sub') {
                    $company_invoice_item_arr[$ary_count]['title'] = 'Secondary Category Listing';
                } else if ($arr_key[1] == 'extra') {
                    $company_invoice_item_arr[$ary_count]['title'] = 'Extra Category Listing';
                }

                $ary_count++;
            }
        }


        if (isset($shopping_cart_content->suggested_products)) {
            $ary_count = count($company_invoice_item_arr);
            foreach ($shopping_cart_content->suggested_products as $key => $product_item) {
                $company_invoice_item_arr[$ary_count]['company_invoice_id'] = $company_invoice2->id;
                $company_invoice_item_arr[$ary_count]['amount'] = $product_item->price;
                $company_invoice_item_arr[$ary_count]['qty'] = '1';
                $company_invoice_item_arr[$ary_count]['total'] = $product_item->price;
                $company_invoice_item_arr[$ary_count]['title'] = $product_item->title;
                $company_invoice_item_arr[$ary_count]['description'] = "";

                $ary_count++;
            }
        }

        //dd($company_invoice_item_arr);
        CompanyInvoiceItem::insert($company_invoice_item_arr);

        return $company_invoice2;
    }

    /* Company Invoice Generate End */

    /* Authorize Payment Start */
    public static function authorizeStripePayment($payment_fields)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
              'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                  'name' => $payment_fields['payment_name'],
                ],
                'unit_amount' => $payment_fields['final_amount'] * 100,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $payment_fields['success_url'],
            'cancel_url' =>  $payment_fields['cancel_url']
          ]);

        return $checkout_session;
    }

    public static function authorizePayment($payment_fields) {
        defined("AUTHORIZENET_LOG_FILE") or define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_NAME'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_KEY'));
        $refId = 'ref' . time();

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($payment_fields['card_number']);
        $creditCard->setExpirationDate($payment_fields['exp_year'] . '-' . $payment_fields['exp_month']);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Set the customer's Bill To address
        $customerBillAddress = new AnetAPI\CustomerAddressType();
        $customerBillAddress->setFirstName($payment_fields['bill_first_name']);
        $customerBillAddress->setLastName($payment_fields['bill_last_name']);
        $customerBillAddress->setCompany($payment_fields['bill_company_name']);
        $customerBillAddress->setAddress($payment_fields['bill_address']);
        $customerBillAddress->setCity($payment_fields['bill_city']);
        $customerBillAddress->setState($payment_fields['bill_state']);
        $customerBillAddress->setZip($payment_fields['bill_zipcode']);
        $customerBillAddress->setCountry($payment_fields['bill_county']);

        // Set the customer's Shipping To address
        $customerShipAddress = new AnetAPI\CustomerAddressType();
        $customerShipAddress->setFirstName($payment_fields['ship_first_name']);
        $customerShipAddress->setLastName($payment_fields['ship_last_name']);
        $customerShipAddress->setCompany($payment_fields['ship_company_name']);
        $customerShipAddress->setAddress($payment_fields['ship_address']);
        $customerShipAddress->setCity($payment_fields['ship_city']);
        $customerShipAddress->setState($payment_fields['ship_state']);
        $customerShipAddress->setZip($payment_fields['ship_zipcode']);
        $customerShipAddress->setCountry($payment_fields['ship_county']);


        // Create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($payment_fields['final_amount']);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerBillAddress);
        $transactionRequestType->setShipTo($customerShipAddress);
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);


        if (env('AUTHORIZE_PAYMENT_MODE') == 'PRODUCTION') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();
            return $tresponse;
        } else {
            return [
                'success' => 0,
                'message' => ''
            ];
        }
    }

    public static function monthly_subscription($payment_fields) {
        //defined("AUTHORIZENET_LOG_FILE") or define("AUTHORIZENET_LOG_FILE", "phplog");
        //define("AUTHORIZENET_LOG_FILE", "phplog");
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_NAME'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_KEY'));

        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($payment_fields['company_name'] . " monthly Subscription");

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength(30);
        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate($payment_fields['subscription_start_date']);
        $paymentSchedule->setTotalOccurrences($payment_fields['subscription_occurance']);
        $paymentSchedule->setTrialOccurrences("1");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($payment_fields['final_amount']);
        /* rand(1, 99999) / 12.0 * 12 */
        $subscription->setTrialAmount($payment_fields['final_amount']);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($payment_fields['card_number']);
        $creditCard->setExpirationDate($payment_fields['exp_year'] . '-' . $payment_fields['exp_month']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($payment_fields['invoice_id']);
        $order->setDescription($payment_fields['invoice_type']);
        $subscription->setOrder($order);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($payment_fields['first_name']);
        $billTo->setLastName($payment_fields['last_name']);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        if (env('AUTHORIZE_PAYMENT_MODE') == 'PRODUCTION') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }

        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null && $response->getMessages()->getResultCode() == 'Ok') {
            //dd($response);
            //$tresponse = $response->getTransactionResponse();
            return $response;
        } else {
            return [
                'success' => 0,
                'message' => ''
            ];
        }
    }

    public static function get_subscription($subscriptionId) {
        defined("AUTHORIZENET_LOG_FILE") or define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_NAME'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Creating the API Request with required parameters
        $request = new AnetAPI\ARBGetSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);
        $request->setIncludeTransactions(true);

        // Controller
        $controller = new AnetController\ARBGetSubscriptionController($request);

        // Getting the response
        if (env('AUTHORIZE_PAYMENT_MODE') == 'PRODUCTION') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }

        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            if ($response->getSubscription()->getStatus() == "active") {
                $transactions = $response->getSubscription()->getArbTransactions();

                return $transactions;
            } else {
                return [
                    'success' => 0,
                    'message' => 'Subscription cancelled.'
                ];
            }
        } else {
            return [
                'success' => 0,
                'message' => ''
            ];
        }
    }

    public static function cancel_subscription($subscriptionId) {
        defined("AUTHORIZENET_LOG_FILE") or define("AUTHORIZENET_LOG_FILE", "phplog");

        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_NAME'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);

        if (env('AUTHORIZE_PAYMENT_MODE') == 'PRODUCTION') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }

        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $successMessages = $response->getMessages()->getMessage();
            return [
                'success' => 1,
                'message' => $successMessages[0]->getText()
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            return [
                'success' => 0,
                'message' => "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText()
            ];
        }
    }

    /* Authorize Payment End */


    /* Tazworks API call start */

    public static function tazworksapi($content, $api_link = null) {


        if ($api_link == null) {
            $apiUrl = env('SANDBOX_API_LINK');
        } else {
            $apiUrl = $api_link;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_URL, $apiUrl); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);

        $data = curl_exec($ch);
        curl_close($ch);
        $response = simplexml_load_string($data);
        $tresponse = json_encode($response);
        return json_decode($tresponse, true);
    }

    /* Tazworks API call end */

    /* Company background check submittal process for owners start */

    public static function owner_background_check_submittal($company_id, $number_of_owners) {
        $submitted_user_count = CompanyUser::where('company_id', $company_id)
                ->whereNotNull('bg_check_status')
                ->active()
                ->order()
                ->count();


        if ($submitted_user_count == $number_of_owners) {
            $company_approval_status = CompanyApprovalStatus::firstOrCreate(['company_id' => $company_id]);
            $company_approval_status->background_check_submittal = 'completed';
            $company_approval_status->background_check_process = 'in process';
            //$company_approval_status->pre_screening_process = 'in process';
            $company_approval_status->save();
        }
    }

    /* Company background check submittal process for owners end */

    public static function company_bio_logo_card_colors($type, $param = null) {
        $returnArr = [];
        if ($type == 'pending' && !is_null($param)) {
            $returnArr = [
                'card_cls' => 'card-danger',
                'card_header_cls' => 'border-danger',
                'card_text_cls' => 'text-danger'
            ];
        } else if ($type == 'pending') {
            $returnArr = [
                'card_cls' => 'card-info',
                'card_header_cls' => 'border-info',
                'card_text_cls' => 'text-info'
            ];
        } else if ($type == 'completed') {
            $returnArr = [
                'card_cls' => 'card-success',
                'card_header_cls' => 'border-success',
                'card_text_cls' => 'text-success'
            ];
        } else {
            $returnArr = [
                'card_cls' => 'card-primary',
                'card_header_cls' => 'border-primary',
                'card_text_cls' => 'text-primary'
            ];
        }

        return $returnArr;
    }

    public static function company_document_card_colors($type, $param = null) {
        $returnArr = [];
        if ($type == 'completed') {
            $returnArr = [
                'card_cls' => 'card-primary',
                'card_header_cls' => 'border-primary',
                'card_text_cls' => 'text-primary'
            ];
        } else if (is_null($param) && $type == 'pending') {
            $returnArr = [
                'card_cls' => 'card-primary',
                'card_header_cls' => 'border-primary',
                'card_text_cls' => 'text-primary'
            ];
        } else if (!is_null($param) && $type == 'pending') {
            $returnArr = [
                'card_cls' => 'card-danger',
                'card_header_cls' => 'border-danger',
                'card_text_cls' => 'text-danger'
            ];
        } else {
            $returnArr = [
                'card_cls' => 'card-primary',
                'card_header_cls' => 'border-primary',
                'card_text_cls' => 'text-primary'
            ];
        }

        return $returnArr;
    }

    /* Company Apporval Status check and change start */

    public static function company_approval_status($company_id) {
        // check company is paid membership or not
        $companyObj = Company::with(['membership_level', 'company_information'])->find($company_id);

        if ($companyObj->membership_level->paid_members == 'yes') {
            /* change background check process status start */
            $bg_check_status = CompanyApprovalStatus::where([
                        ['company_id', $company_id]
                    ])->first();

            $bg_check_arr = [
                'owner_1_bg_check_document_status',
                'owner_2_bg_check_document_status',
                'owner_3_bg_check_document_status',
                'owner_4_bg_check_document_status',
            ];

            if (!is_null($bg_check_status)) {
                foreach ($bg_check_arr as $column_item) {
                    if ($bg_check_status->$column_item == 'completed') {
                        $change_status = true;
                    } else {
                        if ($bg_check_status->$column_item == 'not required') {

                        } else {
                            $change_status = false;
                            break;
                        }
                    }
                }

                if (isset($change_status) && $change_status) {
                    $bg_check_status->background_check_submittal = 'completed';
                    $bg_check_status->background_check_process = 'completed';
                    $bg_check_status->save();
                }
            }
            /* change background check process status end */



            /* change pre screen status start */
            $pre_screen_status = CompanyApprovalStatus::where([
                        ['company_id', $company_id]
                    ])->first();

            $statusColumnsArr = [
                'background_check_pre_screen_fees',
                'one_time_setup_fee',
                'background_check_submittal',
                'background_check_process',
                'online_application',
                'registered_legally_to_state',
                'proof_of_ownership',
                'state_licensing',
                'country_licensing',
                'city_licensing',
                'work_agreements_warranty',
                'subcontractor_agreement',
                'general_liablity_insurance_file',
                'worker_comsensation_insurance_file',
                'customer_references',
                'subcontractor_agreement',
                'company_logo',
                'company_bio',
                /* 'owner_1_bg_check_document_status',
                  'owner_2_bg_check_document_status',
                  'owner_3_bg_check_document_status',
                  'owner_4_bg_check_document_status', */
                'online_reputation_report_status',
                'credit_check_report_status',
            ];

            if (!is_null($pre_screen_status)) {
                foreach ($statusColumnsArr as $column_item) {
                    if ($column_item == 'background_check_process' && ($pre_screen_status->$column_item == 'completed' || $pre_screen_status->$column_item == 'in process')) {
                        $change_status = true;
                    } else if ($pre_screen_status->$column_item == 'completed') {
                        $change_status = true;
                    } else {
                        if ($pre_screen_status->$column_item == 'not required') {

                        } else {
                            $change_status = false;
                            break;
                        }
                    }
                }

                if (isset($change_status) && $change_status) {
                    $pre_screen_status->pre_screening_process = 'in process';
                    $company_licensing = CompanyLicensing::where('company_id', $company_id)->first();
                    if (!is_null($company_licensing->pre_screening_report_file_id)) {
                        $pre_screen_status->pre_screening_process = 'completed';
                    }

                    $pre_screen_status->save();
                }
            }
            /* change pre screen status end */


            $company_approval_status = CompanyApprovalStatus::where([
                        ['company_id', $company_id]
                    ])->first();

            $statusColumnsArr = [
                'background_check_pre_screen_fees',
                'one_time_setup_fee',
                'background_check_submittal',
                'background_check_process',
                'pre_screening_process',
                'online_application',
                'registered_legally_to_state',
                'proof_of_ownership',
                'state_licensing',
                'country_licensing',
                'city_licensing',
                'work_agreements_warranty',
                'subcontractor_agreement',
                //'insurance_documents',
                'general_liablity_insurance_file',
                'worker_comsensation_insurance_file',
                'customer_references',
                'subcontractor_agreement',
                'company_logo',
                'company_bio',
                'owner_1_bg_check_document_status',
                'owner_2_bg_check_document_status',
                'owner_3_bg_check_document_status',
                'owner_4_bg_check_document_status',
                'online_reputation_report_status',
                'credit_check_report_status',
            ];

            $required_document = $completed_document = 0;
            if (!is_null($company_approval_status)) {
                foreach ($statusColumnsArr as $column_item) {
                    if ($company_approval_status->$column_item != 'not required') {
                        $required_document++;
                    }

                    if ($company_approval_status->$column_item != 'not required' && $company_approval_status->$column_item == 'completed') {
                        $completed_document++;
                    }
                }

                //echo  $required_document . ' - ' . $completed_document;

                $send_mail = false;
                if ($companyObj->status != 'Active') {
                    if ($required_document == $completed_document) {
                        $company_status = "Final Review";


                        $companyObj->status = "Final Review";
                        $companyObj->save();

                        $send_mail = true;
                    } else {
                        $companyObj = Company::with('company_information')->find($company_id);


                        if ($companyObj->status != 'Pending Approval') {
                            $company_status = "Pending Approval";

                            $companyObj->status = "Pending Approval";
                            $companyObj->save();

                            $send_mail = true;
                        }
                    }
                }


                if ($send_mail) {
                    /* Company status changed mail to Company */
                    $web_settings = self::getSettings();
                    $companyUserObj = CompanyUser::where([
                                ['company_id', $companyObj->id],
                                ['company_user_type', 'company_super_admin']
                            ])
                            ->first();
                    $mail_id = "25"; /* Mail title: Company Status Change - Final Review */
                    $mailArr = self::generate_company_user_email_arr($companyObj->company_information);
                    $replaceWithArr = [
                        'company_name' => $companyObj->company_name,
                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
                        'request_generate_link' => $companyUserObj->email,
                        'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
                        'url' => url('dashboard'),
                        'email_footer' => $companyUserObj->email,
                        'copyright_year' => date('y'),
                            //'main_service_category' => '',
                    ];

                    $messageArr = [
                        'company_id' => $companyObj->id,
                        'message_type' => 'info',
                        'link' => url('dashboard'),
                    ];
                    self::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
                    if (!is_null($mailArr) && count($mailArr) > 0) {
                        foreach ($mailArr as $mail_item) {
                            Mail::to($mail_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                        }
                    }

                    $web_settings = self::getSettings();
                    if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
                        /* ppl invoice generate mail to Admin */
                        $admin_mail_id = "72"; /* Mail title: Company Status Change - Admin */
                        $adminReplaceArr = [
                            'company_name' => $companyObj->company_name,
                            'company_status' => $company_status
                        ];
                        Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceArr));
                    }
                }
            }
        }
    }

    /* Company Apporval Status check and change end */


    /* Company Message Insertion start */

    public static function companyMessageCreate($messageArr) {
        $insertArr = [
            'checked' => 'no',
            'deleted' => 'no'
        ];

        $insertArr = array_merge($insertArr, $messageArr);
        CompanyMessage::create($insertArr);
    }

    public static function companyMailMessageCreate($messageArr, $mail_id, $replaceArr) {
        $emailObj = NewEmail::find($mail_id);
        $footer_variables = config('common_email_keywords');
        $followup_variables = config('new_email_keywords.' . $emailObj->title);

        $toReplaceArr = $footer_variables;
        if (isset($followup_variables) && count($followup_variables) > 0) {
            $toReplaceArr = array_merge($followup_variables, $footer_variables);
        }

        $findArr = array_values($toReplaceArr);

        if (count($replaceArr) > 0) {

            $replaceArr = array_values($replaceArr);

            $subject = str_ireplace($findArr, $replaceArr, $emailObj->subject);

            //$email_header = str_ireplace($findArr, $replaceArr, $emailObj->email_header);
            $email_content = str_ireplace($findArr, $replaceArr,  $emailObj->content);
            //$email_footer = str_ireplace($findArr, $replaceArr, $emailObj->email_footer);
        } else {
            $subject = $emailObj->subject;

            //$email_header = $emailObj->email_header;
            $email_content = $emailObj->content;
            //$email_footer = $emailObj->email_footer;
        }
        //$message = $email_header . '' . $email_content . '' . $email_footer;
        $message = $email_content;
        $insertArr = [
            'title' => $subject,
            'content' => $message,
            'checked' => 'no',
            'deleted' => 'no'
        ];

        $insertArr = array_merge($insertArr, $messageArr);
        CompanyMessage::create($insertArr);
    }

    public static function companyMailMessageCreateWithIntelligentReplace($messageArr, $mail_id, $replaceArr) {
        $emailObj = NewEmail::find($mail_id);
        $footer_variables = config('common_email_keywords');
        $followup_variables = config('new_email_keywords.' . $emailObj->title);

        $toReplaceArr = $footer_variables;
        if (isset($followup_variables) && count($followup_variables) > 0) {
            $toReplaceArr = array_merge($followup_variables, $footer_variables);
        }

        $findArr = array_values($toReplaceArr);

        if (count($replaceArr) > 0) {
            $content = $emailObj->content;

            foreach ($toReplaceArr as $key => $search) {
                if (isset($replaceArr[$key])) {
                    $replace = $replaceArr[$key];
                    $content = str_ireplace($search, $replace, $content);
                }
            }

            $replaceArr = array_values($replaceArr);

            $subject = str_ireplace($findArr, $replaceArr, $emailObj->subject);

            //$email_header = str_ireplace($findArr, $replaceArr, $emailObj->email_header);
            $email_content = str_ireplace($findArr, $replaceArr,  $content);
            //$email_footer = str_ireplace($findArr, $replaceArr, $emailObj->email_footer);
        } else {
            $subject = $emailObj->subject;

            //$email_header = $emailObj->email_header;
            $email_content = $emailObj->content;
            //$email_footer = $emailObj->email_footer;
        }
        //$message = $email_header . '' . $email_content . '' . $email_footer;
        $message = $email_content;
        $insertArr = [
            'title' => $subject,
            'content' => $message,
            'checked' => 'no',
            'deleted' => 'no'
        ];

        $insertArr = array_merge($insertArr, $messageArr);
        CompanyMessage::create($insertArr);
    }

    /* Company Message Insertion end */


    /* Company Lead Generate and send mail Process Start */

    public static function generateCompanyLeads($lead_detail) {
        $company_lead_ids = [];

        if (
                isset($lead_detail->zipcode) && !is_null($lead_detail->zipcode) &&
                isset($lead_detail->service_category_id) && !is_null($lead_detail->service_category_id)
        ) {
            $paid_counter = 1;
            $paid_companies = Company::select('companies.id', 'companies.temporary_budget', 'membership_levels.hide_leads', 'membership_levels.charge_type', 'company_service_categories.fee')
                    ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                    ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                    ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                    ->with('company_lead_notification')
                    ->where([
                        ['membership_levels.paid_members', 'yes'],
                        ['membership_levels.lead_access', 'yes'],
                        ['membership_levels.slug', '!=', 'accredited-member'],
                        ['membership_levels.status', 'active'],
                        ['company_zipcodes.zip_code', $lead_detail->zipcode],
                        ['company_zipcodes.status', 'active'],
                        ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                        ['company_service_categories.status', 'active'],
                    ])
                    ->when(!empty($lead_detail->company_slugs_csv), function ($query) use ($lead_detail) {
                        $slugs = array_filter(array_map('trim', explode(',', $lead_detail->company_slugs_csv)));
                        if (!empty($slugs)) {
                            $query->whereIn('companies.slug', $slugs);
                        }
                    })
                    ->leadStatus('active')
                    ->active()
                    ->orderBy('companies.activated_at', 'ASC')
                    //->orderBy('companies.membership_level_id', 'ASC')
                    ->limit(3)
                    ->get();

            if (count($paid_companies) > 0) {
                foreach ($paid_companies as $i => $company_item) {
                    $insertLead = true;
                    if ($company_item->charge_type == 'ppl_price' && $lead_detail->timeframe != 'Price Shopping - Price Comparing') {
                        // check monthly budget exceed or not
                        $current_used_budget = CompanyLead::where('company_leads.company_id', $company_item->id)
                                ->leftJoin('leads', 'company_leads.lead_id', 'leads.id')
                                ->where(function ($q) {
                                    $q->whereNull('leads.dispute_status');
                                    $q->orWhereIn('leads.dispute_status', ['in process', 'declined', 'cancelled']);
                                })
                                ->where(DB::raw('MONTH(company_leads.created_at)'), now()->format('m'))
                                ->sum('company_leads.fee');

                        /* $current_used_budget = CompanyLead::where('company_id', $company_item->id)
                          ->where(DB::raw('MONTH(created_at)'), now()->format('m'))
                          ->sum('fee'); */

                        if ($current_used_budget >= $company_item->temporary_budget) {
                            $insertLead = false;
                        }

                        $remaining_budget = $company_item->temporary_budget - $current_used_budget;
                        if ($remaining_budget < $company_item->fee) {
                            $insertLead = false;
                        }
                    }

                    if ($insertLead) {
                        $insertArr = [
                            'company_id' => $company_item->id,
                            'lead_id' => $lead_detail->id,
                            'is_hidden' => $company_item->hide_leads,
                            'priority' => $i + 1
                        ];
                        if ($company_item->charge_type == 'ppl_price' && $lead_detail->timeframe != 'Price Shopping - Price Comparing') {
                            $insertArr['fee'] = $company_item->fee;
                        }

                        $company_lead = CompanyLead::create($insertArr);

                        $company_lead_ids[] = $company_lead->id;
                        $paid_counter++;
                    }
                }
            }


            // send leads for accrediation
            if ($paid_counter < 3) {
                $paid_company_counter = Company::select('companies.id', 'companies.temporary_budget', 'membership_levels.hide_leads', 'membership_levels.charge_type', 'company_service_categories.fee')
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->with('company_lead_notification')
                        ->where([
                            ['membership_levels.paid_members', 'yes'],
                            ['membership_levels.lead_access', 'yes'],
                            ['membership_levels.slug', '!=', 'accredited-member'],
                            ['membership_levels.status', 'active'],
                            ['company_zipcodes.zip_code', $lead_detail->zipcode],
                            ['company_zipcodes.status', 'active'],
                            ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                            ['company_service_categories.status', 'active'],
                        ])
                        ->whereIn('companies.status', ['Unpaid Invoice', 'Suspended With Cause', 'Declined Payment', 'Temporarily Suspended'])
                        ->leadStatus('active')
                        ->orderBy('companies.activated_at', 'ASC')
                        //->orderBy('companies.membership_level_id', 'ASC')
                        ->count();

                //Unpaid Invoice, Suspended With Cause, Declined Payment, Temporarily Suspended

                if ($paid_company_counter >= 6) {
                    $accredited_companies = Company::select('companies.id', 'membership_levels.hide_leads')
                            ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                            ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                            ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                            ->where([
                                ['membership_levels.paid_members', 'yes'],
                                ['membership_levels.lead_access', 'yes'],
                                ['membership_levels.slug', 'accredited-member'],
                                ['membership_levels.status', 'active'],
                                ['company_zipcodes.zip_code', $lead_detail->zipcode],
                                ['company_zipcodes.status', 'active'],
                                ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                                ['company_service_categories.status', 'active'],
                            ])
                            ->leadStatus('active')
                            ->active()
                            ->orderBy('companies.activated_at', 'ASC')
                            ->get();

                    if (count($accredited_companies) > 0) {
                        $lead_priority = $paid_counter;
                        foreach ($accredited_companies as $company_item) {
                            $insertArr = [
                                'company_id' => $company_item->id,
                                'lead_id' => $lead_detail->id,
                                'is_hidden' => $company_item->hide_leads,
                                'priority' => $lead_priority
                            ];

                            $company_lead = CompanyLead::create($insertArr);

                            $company_lead_ids[] = $company_lead->id;
                            $lead_priority++;
                        }
                    }
                }
            }


            // preview trial leads
            $preview_trial_companies = Company::select('companies.id', 'membership_levels.hide_leads')
                    ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                    ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                    ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                    ->where([
                        /* Added on 17-2-2020 */
                        //['companies.company_subscribe_status', 'subscribed'],
                        /* Added on 12-3-2020 */
                        ['companies.status', 'Subscribed'],
                        ['membership_levels.id', '1'],
                        ['membership_levels.lead_access', 'yes'],
                        ['membership_levels.status', 'active'],
                        ['company_zipcodes.zip_code', $lead_detail->zipcode],
                        ['company_zipcodes.status', 'active'],
                        ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                        ['company_service_categories.status', 'active'],
                    ])
                    //->leadStatus('active')
                    ->order()
                    ->get();

            if (count($preview_trial_companies) > 0) {
                $get_id = CompanyLead::where('lead_id', $lead_detail->id)->count();

                foreach ($preview_trial_companies as $company_item) {
                    $get_id++;
                    $insertArr = [
                        'company_id' => $company_item->id,
                        'lead_id' => $lead_detail->id,
                        'is_hidden' => $company_item->hide_leads,
                        'priority' => $get_id
                    ];

                    $company_lead = CompanyLead::create($insertArr);
                    $company_lead_ids[] = $company_lead->id;
                }
            }
        }

        //dd($company_lead_ids);
        self::lead_generation_email_to_company($company_lead_ids);
    }

    public static function lead_generation_email_to_company($company_lead_ids) {
        if (count($company_lead_ids) > 0) {
            $company_leads = CompanyLead::with(['company', 'lead'])
                    ->whereIn('id', $company_lead_ids)
                    ->isNotChecked()
                    ->order()
                    ->get();

            if (count($company_leads) > 0) {
                $owners_list = ['owner_2', 'owner_3', 'owner_4', 'office_manager', 'sales_manager', 'estimators_sales_1', 'estimators_sales_2'];
                $owners_list_email = ['owner_2_email', 'owner_3_email', 'owner_4_email', 'office_manager_email', 'sales_manager_email', 'estimators_sales_1_email', 'estimators_sales_2_email'];

                foreach ($company_leads as $company_lead_item) {
                    if (!is_null($company_lead_item->company->company_lead_notification)) {
                        $company_lead_notifications = $company_lead_item->company->company_lead_notification;
                        $email_addresses = [
                            $company_lead_notifications->main_email_address
                        ];

                        if ($company_lead_notifications->receive_a_copy == 'yes') {
                            foreach ($owners_list as $i => $owner_item) {
                                $owner_email = $owners_list_email[$i];
                                if ($company_lead_notifications->$owner_item == 'yes' && !is_null($company_lead_notifications->$owner_email)) {
                                    $email_addresses[] = $company_lead_notifications->$owner_email;
                                }
                            }
                        }

                        if (count($email_addresses) > 0) {
                            foreach ($email_addresses as $index => $email_address_item) {
                                /* Lead Generation mail to Company Mail */
                                /* Mail title: Company Get Lead */
                                if (!is_null($company_lead_item->lead->lead_generate_for)) {
                                    $mail_id = "124";
                                    if ($company_lead_item->company->membership_level_id == 1) {
                                        $mail_id = "126";
                                    }
                                    $url = url('/', ['company_slug' => $company_lead_item->company->slug]);
                                } else {
                                    if ($company_lead_item->company->membership_level_id == 1) {
                                        $mail_id = "126";
                                    } else if ($company_lead_item->company->status == 'Pending Approval' || $company_lead_item->company->status == 'Paid Pending' || $company_lead_item->company->status == 'Approved') {
                                        $mail_id = "127";
                                    } else {
                                        $mail_id = "54";
                                    }

                                    $url = url('leads-archive-inbox');
                                }
                                $web_settings = self::getSettings();
                                if ($company_lead_item->is_hidden == 'yes') {
                                    $replaceWithArr = [
                                        'company_name' => $company_lead_item->company->company_name,
                                        'customer_name' => 'Hidden',
                                        'customer_phone' => 'Hidden',
                                        'customer_email' => 'Hidden',
                                        'street' => 'Hidden',
                                        'zipcode' => $company_lead_item->lead->zipcode,
                                        'main_service_category' => $company_lead_item->lead->main_category->title,
                                        'service_category' => $company_lead_item->lead->service_category->title,
                                        'project_info' => 'Hidden',
                                        'city' => 'Hidden',
                                        'state' => 'Hidden',
                                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                                        'account_type' => '',
                                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_lead_item->company->slug]),
                                        'request_generate_link' => $company_lead_item->email,
                                        'date' => $company_lead_item->created_at->format(env('DATE_FORMAT')),
                                        'url' => $url,
                                        'email_footer' => $company_lead_item->email,
                                        'copyright_year' => date('y')
                                    ];
                                } else {
                                    $replaceWithArr = [
                                        'company_name' => $company_lead_item->company->company_name,
                                        'customer_name' => $company_lead_item->lead->full_name,
                                        'customer_phone' => $company_lead_item->lead->phone,
                                        'customer_email' => $company_lead_item->lead->email,
                                        'street' => $company_lead_item->lead->project_address,
                                        'zipcode' => $company_lead_item->lead->zipcode,
                                        'main_service_category' => $company_lead_item->lead->main_category->title,
                                        'service_category' => $company_lead_item->lead->service_category->title,
                                        'project_info' => $company_lead_item->lead->content,
                                        'city' =>  $company_lead_item->lead->city,
                                        'state' =>  $company_lead_item->lead->state->name,
                                        'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
                                        'account_type' => '',
                                        'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                                        'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                                        'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                                        'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                                        'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                                        'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                                        'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                                        'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_lead_item->company->slug]),
                                        'request_generate_link' => $company_lead_item->email,
                                        'date' => $company_lead_item->created_at->format(env('DATE_FORMAT')),
                                        'url' => $url,
                                        'email_footer' => $company_lead_item->email,
                                        'copyright_year' => date('y')
                                    ];
                                }


                                if ($index == 0) {
                                  $messageArr = [
                                  'company_id' => $company_lead_item->company->id,
                                  'message_type' => 'info',
                                  'link' => $url,
                                  ];

                                  if($mail_id == "54")
                                  {
                                    $replaceWithArr["lead_domain"] = isset($company_lead_item->lead->affiliate->domain)
                                    ? strtoupper($company_lead_item->lead->affiliate->domain) : strtoupper(env('APP_NAME'));
                                    self::companyMailMessageCreateWithIntelligentReplace($messageArr, $mail_id, $replaceWithArr);
                                  }
                                  else
                                  {
                                    self::companyMailMessageCreate($messageArr, $mail_id, $replaceWithArr);
                                  }

                                }

                                if($mail_id == "54")
                                {
                                    $replaceWithArr["lead_domain"] = isset($company_lead_item->lead->affiliate->domain)
                                    ? strtoupper($company_lead_item->lead->affiliate->domain) : strtoupper(env('APP_NAME'));
                                    Mail::to($email_address_item)->send(new CompanyMailV1($mail_id, $replaceWithArr));
                                }
                                else
                                {
                                    Mail::to($email_address_item)->send(new CompanyMail($mail_id, $replaceWithArr));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* Company Lead Generate and send mail Process End */



    /* Company User mail array generate process start  */

    public static function generate_company_user_email_arr($company_information) {
        $mailArr = [];

        /* get company super admin email */
        $company_super_admin = CompanyUser::where([
                    ['company_id', $company_information->company_id],
                    ['company_user_type', 'company_super_admin']
                ])->first();

        $mailArr[] = $company_super_admin->email;
        /* if (!is_null($company_information)) {
          if ($company_information->company_owner_1_status == 'registered' && !is_null($company_information->company_owner_1_email)) {
          $mailArr[] = $company_information->company_owner_1_email;
          }

          if ($company_information->company_owner_2_status == 'registered' && !is_null($company_information->company_owner_2_email)) {
          $mailArr[] = $company_information->company_owner_2_email;
          }

          if ($company_information->company_owner_3_status == 'registered' && !is_null($company_information->company_owner_3_email)) {
          $mailArr[] = $company_information->company_owner_3_email;
          }

          if ($company_information->company_owner_4_status == 'registered' && !is_null($company_information->company_owner_4_email)) {
          $mailArr[] = $company_information->company_owner_4_email;
          }
          } */

        return $mailArr;
    }

    /* Company User mail array generate process end  */



    /* Send Broadcast emails start */

    public static function send_broadcast_email($broadcast_email_id) {
        $broadcast_email = BroadcastEmail::find($broadcast_email_id);

        //DB::enableQueryLog();

        $companies = Company::select('companies.*')->with('ppl_company_information');

        if (!is_null($broadcast_email->trade_id)) {
            $companies->where('companies.trade_id', $broadcast_email->trade_id);
        }

        if (!is_null($broadcast_email->top_level_category_id)) {
            $companies->leftJoin('company_service_categories AS cs', 'companies.id', 'cs.company_id')
                    ->where('cs.top_level_category_id', $broadcast_email->top_level_category_id);
        }

        if (!is_null($broadcast_email->main_category_id)) {
            $companies->leftJoin('company_service_categories AS cs1', 'companies.id', 'cs1.company_id')
                    ->where('cs1.main_category_id', $broadcast_email->main_category_id);
        }

        if (!is_null($broadcast_email->service_category_id)) {
            $companies->leftJoin('company_service_categories AS cs2', 'companies.id', 'cs2.company_id')
                    ->where('cs2.service_category_id', $broadcast_email->service_category_id);
        }


        if (!is_null($broadcast_email->zipcode) && !is_null($broadcast_email->mile_range)) {
            try {
                $zipCodes = self::getZipCodeRange($broadcast_email->zipcode, $broadcast_email->mile_range);

                if (count($zipCodes) > 0) {
                    $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                            ->whereIn('cz.zip_code', array_column($zipCodes, 'zip_code'));
                }
            } catch (Exception $e) {

            }
        } else if (!is_null($broadcast_email->zipcode)) {
            $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                    ->where('cz.zip_code', $broadcast_email->zipcode);
        }

        $company_list = $companies->groupBy('companies.id')->get();

        //dd(DB::getQueryLog());
        //dd($company_list);

        if (count($company_list) > 0) {
            foreach ($company_list as $company_item) {
                $mailArr = self::generate_company_user_email_arr($company_item->ppl_company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr as $mail_item) {
                        Mail::to($mail_item)->send(new BroadcastMail($broadcast_email->content, $broadcast_email->subject));
                    }
                }
            }
        }

        return [
            'success' => 1
        ];
    }

    public static function send_non_member_broadcast_email($broadcast_email) {
        $companies = NonMember::select('non_members.*')->active()->order();

        if (!is_null($broadcast_email->trade_id)) {
            $companies->where('non_members.trade_id', $broadcast_email->trade_id);
        }

        if (!is_null($broadcast_email->top_level_category_id)) {
            $companies->leftJoin('non_member_top_level_categories AS nmtlc', 'non_members.id', 'nmtlc.non_member_id')
                    ->where('nmtlc.top_level_category_id', $broadcast_email->top_level_category_id);
        }

        if (!is_null($broadcast_email->zipcode) && !is_null($broadcast_email->mile_range)) {
            try {
                $zipCodes = self::getZipCodeRange($broadcast_email->zipcode, $broadcast_email->mile_range);

                if (count($zipCodes) > 0) {
                    $companies->leftJoin('non_member_zipcodes AS nmz', 'non_members.id', 'nmz.non_member_id')
                            ->whereIn('nmz.zipcode', array_column($zipCodes, 'zip_code'));
                }
            } catch (Exception $e) {

            }
        } else if (!is_null($broadcast_email->zipcode)) {
            $companies->where('non_members.zipcode', $broadcast_email->zipcode);
        }

        $company_list = $companies->groupBy('non_members.id')->get();

        $web_settings = self::getSettings();

        if (count($company_list) > 0) {
            foreach ($company_list as $company_item) {
                $replaceArr = [
                    'company_name' => $company_item->company_name,
                    'company_owner_name' => $company_item->first_name . ' ' . $company_item->last_name,
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('get-listed/unsubscribe-page', ['company_id' => $company_item->id]),
                    'request_generate_link' => $company_item->email,
                    'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                    'url' => url('get-listed'),
                    'email_footer' => $company_item->email,
                    'copyright_year' => date('y'),
                ];

                Mail::to($company_item->email)->send(new BroadcastMail($broadcast_email->id, $replaceArr));
            }
        }

        return [
            'success' => 1
        ];
    }

    public static function send_registered_member_broadcast_email($broadcast_email) {
        $companies = Company::select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.paid_members', 'no')
                ->with('ppl_company_information');

        if (!is_null($broadcast_email->email_for)) {
            $companies->where('companies.membership_level_id', $broadcast_email->email_for);
        }

        if (!is_null($broadcast_email->subscription_type)) {
            $subscription_type = $broadcast_email->subscription_type;
            $companies->where($subscription_type, 'subscribe');
        }

        if (!is_null($broadcast_email->trade_id)) {
            $companies->where('companies.trade_id', $broadcast_email->trade_id);
        }

        if (!is_null($broadcast_email->top_level_category_id)) {
            $companies->leftJoin('company_service_categories AS cs', 'companies.id', 'cs.company_id')
                    ->where('cs.top_level_category_id', $broadcast_email->top_level_category_id);
        }

        if (!is_null($broadcast_email->main_category_id)) {
            $companies->leftJoin('company_service_categories AS cs1', 'companies.id', 'cs1.company_id')
                    ->where('cs1.main_category_id', $broadcast_email->main_category_id);
        }

        if (!is_null($broadcast_email->service_category_id)) {
            $companies->leftJoin('company_service_categories AS cs2', 'companies.id', 'cs2.company_id')
                    ->where('cs2.service_category_id', $broadcast_email->service_category_id);
        }


        if (!is_null($broadcast_email->zipcode) && !is_null($broadcast_email->mile_range)) {
            try {
                $zipCodes = self::getZipCodeRange($broadcast_email->zipcode, $broadcast_email->mile_range);

                if (count($zipCodes) > 0) {
                    $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                            ->whereIn('cz.zip_code', array_column($zipCodes, 'zip_code'));
                }
            } catch (Exception $e) {

            }
        } else if (!is_null($broadcast_email->zipcode)) {
            $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                    ->where('cz.zip_code', $broadcast_email->zipcode);
        }

        $company_list = $companies->groupBy('companies.id')->get();

        //dd(DB::getQueryLog());
        //dd($company_list);

        $web_settings = self::getSettings();
        if (count($company_list) > 0) {
            foreach ($company_list as $company_item) {
                if ($company_item->membership_level_id == 1) {
                    $url = url('preview-trial');
                } else if ($company_item->membership_level_id == 2) {
                    $url = url('full-listing');
                } else if ($company_item->membership_level_id == 3) {
                    $url = url('accreditation');
                }

                $mailArr = self::generate_company_user_email_arr($company_item->ppl_company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr as $mail_item) {
                        $company_owner = CompanyUser::where([
                                    ['company_id', $company_item->id],
                                    ['email', $mail_item]
                                ])
                                ->first();


                        $replaceArr = [
                            'company_name' => $company_item->company_name,
                            'company_owner_name' => $company_owner->first_name . ' ' . $company_owner->last_name,
                            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                            'request_generate_link' => $mail_item,
                            'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                            'url' => $url,
                            'email_footer' => $mail_item,
                            'copyright_year' => date('y'),
                        ];

                        Mail::to($mail_item)->send(new BroadcastMail($broadcast_email->id, $replaceArr));
                    }
                }
            }
        }

        return [
            'success' => 1
        ];
    }

    public static function send_official_member_broadcast_email($broadcast_email) {
        $companies = Company::select('companies.*')
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->where('membership_levels.paid_members', 'yes')
                ->with('ppl_company_information');

        if (!is_null($broadcast_email->email_for)) {
            $companies->where('companies.membership_level_id', $broadcast_email->email_for);
        }

        if (!is_null($broadcast_email->trade_id)) {
            $companies->where('companies.trade_id', $broadcast_email->trade_id);
        }

        if (!is_null($broadcast_email->top_level_category_id)) {
            $companies->leftJoin('company_service_categories AS cs', 'companies.id', 'cs.company_id')
                    ->where('cs.top_level_category_id', $broadcast_email->top_level_category_id);
        }

        if (!is_null($broadcast_email->main_category_id)) {
            $companies->leftJoin('company_service_categories AS cs1', 'companies.id', 'cs1.company_id')
                    ->where('cs1.main_category_id', $broadcast_email->main_category_id);
        }

        if (!is_null($broadcast_email->service_category_id)) {
            $companies->leftJoin('company_service_categories AS cs2', 'companies.id', 'cs2.company_id')
                    ->where('cs2.service_category_id', $broadcast_email->service_category_id);
        }


        if (!is_null($broadcast_email->zipcode) && !is_null($broadcast_email->mile_range)) {
            try {
                $zipCodes = self::getZipCodeRange($broadcast_email->zipcode, $broadcast_email->mile_range);

                if (count($zipCodes) > 0) {
                    $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                            ->whereIn('cz.zip_code', array_column($zipCodes, 'zip_code'));
                }
            } catch (Exception $e) {

            }
        } else if (!is_null($broadcast_email->zipcode)) {
            $companies->leftJoin('company_zipcodes AS cz', 'companies.id', 'cz.company_id')
                    ->where('cz.zip_code', $broadcast_email->zipcode);
        }

        $company_list = $companies->groupBy('companies.id')->get();

        //dd(DB::getQueryLog());
        //dd($company_list);

        $web_settings = self::getSettings();
        if (count($company_list) > 0) {
            foreach ($company_list as $company_item) {

                $url = url('preview-trial');
                if ($company_item->membership_level_id == 7) {
                    $url = url('accreditation');
                }

                $mailArr = self::generate_company_user_email_arr($company_item->ppl_company_information);
                if (!is_null($mailArr) && count($mailArr) > 0) {
                    foreach ($mailArr as $mail_item) {
                        $company_owner = CompanyUser::where([
                                    ['company_id', $company_item->id],
                                    ['email', $mail_item]
                                ])
                                ->first();

                        $replaceArr = [
                            'company_name' => $company_item->company_name,
                            'company_owner_name' => $company_owner->first_name . ' ' . $company_owner->last_name,
                            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $company_item->slug]),
                            'request_generate_link' => $mail_item,
                            'date' => $company_item->created_at->format(env('DATE_FORMAT')),
                            'url' => $url,
                            'email_footer' => $mail_item,
                            'copyright_year' => date('y'),
                        ];

                        /* Updated At : 03-06-2020 03:08 PM */
                        Mail::to($mail_item)->send(new BroadcastMail($broadcast_email->id, $replaceArr));
                    }
                }
            }
        }

        return [
            'success' => 1
        ];
    }

    public static function send_lead_broadcast_email($broadcast_email) {
        $leads = Lead::select('leads.*');

        if (!is_null($broadcast_email->subscription_type)) {
            $subscription_type = $broadcast_email->subscription_type;
            $leads->where($subscription_type, 'subscribe');
        }

        if (!is_null($broadcast_email->trade_id)) {
            $leads->where('leads.trade_id', $broadcast_email->trade_id);
        }

        if (!is_null($broadcast_email->main_category_id)) {
            $leads->where('leads.main_category_id', $broadcast_email->main_category_id);
        }

        if (!is_null($broadcast_email->service_category_id)) {
            $leads->where('leads.service_category_id', $broadcast_email->service_category_id);
        }

        if (!is_null($broadcast_email->zipcode) && !is_null($broadcast_email->mile_range)) {
            try {
                $zipCodes = self::getZipCodeRange($broadcast_email->zipcode, $broadcast_email->mile_range);

                if (count($zipCodes) > 0) {
                    $leads->whereIn('leads.zipcode', array_column($zipCodes, 'zip_code'));
                }
            } catch (Exception $e) {

            }
        } else if (!is_null($broadcast_email->zipcode)) {
            $leads->where('leads.zipcode', $broadcast_email->zipcode);
        }

        $leads_list = $leads->groupBy('leads.id')->get();

        //dd(DB::getQueryLog());
        //dd($leads_list);

        $web_settings = self::getSettings();
        if (count($leads_list) > 0) {
            foreach ($leads_list as $lead_item) {
                if (!is_null($lead_item->lead_generate_for)) {
                    $url = url('/', ['company_slug' => $lead_item->lead_generate_for_company->slug]);
                } else {
                    $url = url('find-a-pro');
                }

                $replaceArr = [
                    'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
                    'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
                    'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
                    'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
                    'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
                    'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
                    'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
                    'unsubscription_link' => url('unsubscribe-page/lead', ['lead_id' => $lead_item->id]),
                    'request_generate_link' => $lead_item->email,
                    'date' => $lead_item->created_at->format(env('DATE_FORMAT')),
                    'url' => $url,
                    'email_footer' => $lead_item->email,
                    'copyright_year' => date('y'),
                ];

                Mail::to($lead_item->email)->send(new BroadcastMail($broadcast_email->id, $replaceArr));
            }
        }

        return [
            'success' => 1
        ];
    }

    /* Send Broadcast emails end */



    /* Delete media start */

    public static function delete_media($mediaObj) {
        $fit_thumbs = explode(',', env('FIT_THUMBS'));
        if (count($fit_thumbs) > 0) {
            foreach ($fit_thumbs as $size_item) {
                if (file_exists('uploads/media/fit_thumbs/' . $size_item . '/' . $mediaObj->file_name)) {
                    unlink('uploads/media/fit_thumbs/' . $size_item . '/' . $mediaObj->file_name);
                }
            }
        }

        $width_thumbs = explode(',', env('HEIGHT_THUMBS'));
        if (count($width_thumbs) > 0) {
            foreach ($width_thumbs as $size_item) {
                if (file_exists('uploads/media/height_thumbs/' . $size_item . '/' . $mediaObj->file_name)) {
                    unlink('uploads/media/height_thumbs/' . $size_item . '/' . $mediaObj->file_name);
                }
            }
        }

        $height_thumbs = explode(',', env('WIDTH_THUMBS'));
        if (count($height_thumbs) > 0) {
            foreach ($height_thumbs as $size_item) {
                if (file_exists('uploads/media/width_thumbs/' . $size_item . '/' . $mediaObj->file_name)) {
                    unlink('uploads/media/width_thumbs/' . $size_item . '/' . $mediaObj->file_name);
                }
            }
        }

        if (file_exists('uploads/media/' . $mediaObj->file_name)) {
            unlink('uploads/media/' . $mediaObj->file_name);
        }

        Media::where('id', $mediaObj->id)->delete();
    }

    /* Delete media end */


    /* Lead confirmation email to consumer start */
    public static function lead_confirmation_email_for_find_a_pro($lead_detail) {
        if (!is_null($lead_detail->lead_generate_for)) {
            $companyObj = Company::where([
                        ['id', $lead_detail->lead_generate_for],
                        ['status', 'Active'],
                        ['leads_status', 'active']
                    ])->first();

            if (!is_null($companyObj)) {
                if ($lead_detail->trade_id == 1) {
                    $category_id = '3';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '8';
                }
            } else {
                if ($lead_detail->trade_id == 1) {
                    $category_id = '4';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '7';
                }
            }

            $leadCompany = Company::find($lead_detail->lead_generate_for);
        } else {
            $lead_counter = self::get_number_of_companies_who_get_leads($lead_detail);
            if ($lead_counter > 0) {
                // Send confirmation email of "Default Find A Pro Have Members"
                if ($lead_detail->trade_id == 1) {
                    $category_id = '2';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '6';
                }
            } else {
                // Send confirmation email of "Default Find A Pro No Members"
                if ($lead_detail->trade_id == 1) {
                    $category_id = '1';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '5';
                }
            }
        }

        $mail_id = FollowUpEmail::where([
                    ['trade_id', $lead_detail->trade_id],
                    ['follow_up_mail_category_id', $category_id]
                ])->confirmationEmail()->active()->first();

        $web_settings = self::getSettings();

        if (!is_null($lead_detail->lead_generate_for)) {
            $url = url('/', ['company_slug' => $leadCompany->slug]);
        } else {
            $url = url('find-a-pro');
        }

        $replaceArr = [
            'first_name' => $lead_detail->full_name,
            'main_service_category' => $lead_detail->main_category->title,
            'top_level_category' => $lead_detail->top_level_category->title,
            'service_category' => $lead_detail->service_category->title,
            'service_category_type' => $lead_detail->service_category_type->title,
            'confirm_request' => url('/activate-lead', ['activation_key' => $lead_detail->lead_activation_key]),
            'email_detail_companies' => '',
            'zipcode' => $lead_detail->zipcode,
            'company_page' => ((isset($leadCompany) && !is_null($leadCompany)) ? url('/', ['company_slug' => $leadCompany->slug]) : ''),
            'company_name' => ((isset($leadCompany) && !is_null($leadCompany)) ? $leadCompany->company_name : ''),
            'company_logo' => '',
            'address' => '',
            'rating' => '',
            'find_a_pro_link' => url('find-a-pro'),
            'verify_member_link' => url('/'),
            'submit_complaint_link' => ((isset($leadCompany) && !is_null($leadCompany)) ? url("/", ['company_slug' => $leadCompany->slug]) : ''),
            /* 'request_generate_link' => $lead_detail->email,
              'date' => $lead_detail->created_at->format(env('DATE_FORMAT')),
              'url' => $url, */
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/lead', ['lead_id' => $lead_detail->id]),
            'request_generate_link' => $lead_detail->email,
            'date' => $lead_detail->created_at->format(env('DATE_FORMAT')),
            'url' => $url,
            'email_footer' => $lead_detail->email,
            'copyright_year' => date('y'),
        ];

        Mail::to($lead_detail->email)->send(new FollowUpMail($mail_id->id, $replaceArr));

        $lead_detail->follow_up_mail_category_id = $category_id;
        $lead_detail->save();
    }
    /* Lead confirmation email to consumer start */
    public static function lead_confirmation_email($lead_detail) {
        if (!is_null($lead_detail->lead_generate_for)) {
            $companyObj = Company::where([
                        ['id', $lead_detail->lead_generate_for],
                        ['status', 'Active'],
                        ['leads_status', 'active']
                    ])->first();

            if (!is_null($companyObj)) {
                if ($lead_detail->trade_id == 1) {
                    $category_id = '3';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '8';
                }
            } else {
                if ($lead_detail->trade_id == 1) {
                    $category_id = '4';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '7';
                }
            }

            $leadCompany = Company::find($lead_detail->lead_generate_for);
        } else {
            $lead_counter = self::get_number_of_companies_who_get_leads($lead_detail);
            if ($lead_counter > 0) {
                // Send confirmation email of "Default Find A Pro Have Members"
                if ($lead_detail->trade_id == 1) {
                    $category_id = '2';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '6';
                }
            } else {
                // Send confirmation email of "Default Find A Pro No Members"
                if ($lead_detail->trade_id == 1) {
                    $category_id = '1';
                } else if ($lead_detail->trade_id == 2) {
                    $category_id = '5';
                }
            }
        }

        //Commenting the mail sending feature as this will be moved to aweber
        // $mail_id = FollowUpEmail::where([
        //             ['trade_id', $lead_detail->trade_id],
        //             ['follow_up_mail_category_id', $category_id]
        //         ])->confirmationEmail()->active()->first();

        // $web_settings = self::getSettings();

        // if (!is_null($lead_detail->lead_generate_for)) {
        //     $url = url('/', ['company_slug' => $leadCompany->slug]);
        // } else {
        //     $url = url('find-a-pro');
        // }

        // $replaceArr = [
        //     'first_name' => $lead_detail->full_name,
        //     'main_service_category' => $lead_detail->main_category->title,
        //     'top_level_category' => $lead_detail->top_level_category->title,
        //     'service_category' => $lead_detail->service_category->title,
        //     'service_category_type' => $lead_detail->service_category_type->title,
        //     'confirm_request' => url('/activate-lead', ['activation_key' => $lead_detail->lead_activation_key]),
        //     'email_detail_companies' => '',
        //     'zipcode' => $lead_detail->zipcode,
        //     'company_page' => ((isset($leadCompany) && !is_null($leadCompany)) ? url('/', ['company_slug' => $leadCompany->slug]) : ''),
        //     'company_name' => ((isset($leadCompany) && !is_null($leadCompany)) ? $leadCompany->company_name : ''),
        //     'company_logo' => '',
        //     'address' => '',
        //     'rating' => '',
        //     'find_a_pro_link' => url('find-a-pro'),
        //     'verify_member_link' => url('/'),
        //     'submit_complaint_link' => ((isset($leadCompany) && !is_null($leadCompany)) ? url("/", ['company_slug' => $leadCompany->slug]) : ''),
        //     /* 'request_generate_link' => $lead_detail->email,
        //       'date' => $lead_detail->created_at->format(env('DATE_FORMAT')),
        //       'url' => $url, */
        //     'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
        //     'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
        //     'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
        //     'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
        //     'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
        //     'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
        //     'unsubscription_link' => url('unsubscribe-page/lead', ['lead_id' => $lead_detail->id]),
        //     'request_generate_link' => $lead_detail->email,
        //     'date' => $lead_detail->created_at->format(env('DATE_FORMAT')),
        //     'url' => $url,
        //     'email_footer' => $lead_detail->email,
        // ];

        //Mail::to($lead_detail->email)->send(new FollowUpMail($mail_id->id, $replaceArr));

        $lead_detail->follow_up_mail_category_id = $category_id;
        $lead_detail->save();
    }

    public static function lead_followup_email($follow_up_email_item) {
        $web_settings = self::getSettings();
        $mail_id = $follow_up_email_item->follow_up_email_id;
        if (!is_null($follow_up_email_item->lead->lead_generate_for)) {
            $url = route('company-page', ['company_slug' => $follow_up_email_item->lead->lead_generate_for_company->slug]);
            $company_name = $follow_up_email_item->lead->lead_generate_for_company->company_name;
        } else {
            $url = url('find-a-pro');
            $company_name = '';
        }

        $data = ['lead_id' => $follow_up_email_item->lead->id];
        $replaceArr = [
            'first_name' => $follow_up_email_item->lead->full_name,
            'main_service_category' => $follow_up_email_item->lead->main_category->title,
            'top_level_category' => $follow_up_email_item->lead->top_level_category->title,
            'service_category' => $follow_up_email_item->lead->service_category->title,
            'service_category_type' => $follow_up_email_item->lead->service_category_type->title,
            'confirm_request' => '',
            'email_detail_companies' => view('mails.followup.company_list_detail', $data)->render(),
            'zipcode' => $follow_up_email_item->lead->zipcode,
            'company_page' => $url,
            'company_name' => $company_name,
            'company_logo' => '',
            'address' => '',
            'rating' => '',
            'find_a_pro_link' => url('find-a-pro'),
            'verify_member_link' => url('/'),
            'submit_complaint_link' => '',
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/lead', ['lead_id' => $follow_up_email_item->lead->id]),
            'request_generate_link' => $follow_up_email_item->lead->email,
            'date' => $follow_up_email_item->lead->created_at->format(env('DATE_FORMAT')),
            'url' => $url,
            'email_footer' => $follow_up_email_item->lead->email,
            'copyright_year' => date('y'),
                //'main_service_category' => $follow_up_email_item->lead->main_category->title,
        ];

        Mail::to($follow_up_email_item->lead->email)->send(new FollowUpMail($mail_id, $replaceArr));
    }

    public static function lead_email_admin($lead_detail) {
        $web_settings = self::getSettings();
        /* Lead active email to Admin */
        if (isset($web_settings['global_email']) && $web_settings['global_email'] != '') {
            $admin_mail_id = "53"; /* Mail title: Find A Pro Activation - Admin */
            $get_lead_data['company_list'] = CompanyLead::with('company_name_admin_list')->where('lead_id', $lead_detail->id)->get();
            $company_list = view('mails.admin._company_list_who_get_lead', $get_lead_data)->render();

            $get_lead_company_list = CompanyLead::where('lead_id', $lead_detail->id)
                    ->pluck('company_id')
                    ->toArray();

            $not_get_lead_data['company_list'] = Company::select('companies.id', 'companies.company_name', 'companies.leads_status', 'companies.status AS membership_status', 'membership_levels.title AS membership_level_title', 'companies.temporary_budget', 'membership_levels.charge_type', 'company_service_categories.fee')
                    ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                    ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                    ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                    ->whereNotIn('companies.id', $get_lead_company_list)
                    ->where([
                        ['membership_levels.paid_members', 'yes'],
                        ['membership_levels.lead_access', 'yes'],
                        ['membership_levels.slug', '!=', 'accredited-member'],
                        ['membership_levels.status', 'active'],
                        ['company_zipcodes.zip_code', $lead_detail->zipcode],
                        ['company_zipcodes.status', 'active'],
                        ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                        ['company_service_categories.status', 'active'],
                    ])
                    ->when(!empty($lead_detail->company_slugs_csv), function ($query) use ($lead_detail) {
                        $slugs = array_filter(array_map('trim', explode(',', $lead_detail->company_slugs_csv)));
                        if (!empty($slugs)) {
                            $query->whereIn('companies.slug', $slugs);
                        }
                    })
                    ->orderBy('companies.created_at', 'ASC')
                    ->get();
            $not_get_lead_company_list = view('mails.admin._company_list_who_didnt_get_lead', $not_get_lead_data)->render();

            $replaceWithArr = [
                'customer_name' => $lead_detail->full_name,
                'customer_phone' => $lead_detail->phone,
                'customer_email' => $lead_detail->email,
                'street' => $lead_detail->project_address,
                'zipcode' => $lead_detail->zipcode,
                'main_service_category' => $lead_detail->main_category->title,
                'service_category' => $lead_detail->service_category->title,
                'project_info' => $lead_detail->content,
                'company_list' => $company_list,
                'not_get_lead_company_list' => $not_get_lead_company_list,
                'city' => $lead_detail->city,
                'state' => $lead_detail->state->name
            ];

            //for testing purpose, replacing this with personal email id
            Mail::to($web_settings['global_email'])->send(new AdminMail($admin_mail_id, $replaceWithArr));
        }
    }

    /* Check any company get lead start */

    public static function get_number_of_companies_who_get_leads($lead_detail) {
        return Company::select(
            'companies.id',
            'companies.temporary_budget',
            'membership_levels.hide_leads',
            'membership_levels.charge_type',
            'company_service_categories.fee'
            )
                ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                ->with('company_lead_notification')
                ->where([
                    ['membership_levels.paid_members', 'yes'],
                    ['membership_levels.lead_access', 'yes'],
                    ['membership_levels.slug', '!=', 'accredited-member'],
                    ['membership_levels.status', 'active'],
                    ['company_zipcodes.zip_code', $lead_detail->zipcode],
                    ['company_zipcodes.status', 'active'],
                    ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                    ['company_service_categories.status', 'active'],
                ])
                ->when(!empty($lead_detail->company_slugs_csv), function ($query) use ($lead_detail) {
                        $slugs = array_filter(array_map('trim', explode(',', $lead_detail->company_slugs_csv)));
                        if (!empty($slugs)) {
                            $query->whereIn('companies.slug', $slugs);
                        }
                    })
                ->leadStatus('active')
                ->active()
                ->orderBy('companies.activated_at', 'ASC')
                //->orderBy('companies.membership_level_id', 'ASC')
                ->limit(3)
                ->count();
    }

    public static function get_companies_who_get_leads($lead_detail) {
        return Company::select(
                                'companies.id',
                                'companies.company_name',
                                'companies.main_company_telephone',
                                'companies.temporary_budget',
                                'membership_levels.hide_leads',
                                'membership_levels.id as membership_level_id',
                                'membership_levels.charge_type',
                                'company_service_categories.fee',
                                'companies.slug'
                                ,DB::raw('IF(m.file_name != "", CONCAT("' . env('APP_URL') . '/uploads/media/", m.file_name), "") AS logo')
                                )
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
                        ->with('company_lead_notification')
                        ->where([
                            ['membership_levels.paid_members', 'yes'],
                            ['membership_levels.lead_access', 'yes'],
                            ['membership_levels.slug', '!=', 'accredited-member'],
                            ['membership_levels.status', 'active'],
                            ['company_zipcodes.zip_code', $lead_detail->zipcode],
                            ['company_zipcodes.status', 'active'],
                            ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                            ['company_service_categories.status', 'active'],
                        ])
                        ->when(!empty($lead_detail->company_slugs_csv), function ($query) use ($lead_detail) {
                            $slugs = array_filter(array_map('trim', explode(',', $lead_detail->company_slugs_csv)));
                            if (!empty($slugs)) {
                                $query->whereIn('companies.slug', $slugs);
                            }
                        })
                        ->leadStatus('active')
                        ->active()
                        //->inRandomOrder()
                        ->orderBy('companies.activated_at', 'ASC')
                        ->orderBy('companies.membership_level_id', 'ASC')
                        ->limit(3)
                        ->get();
    }

    public static function get_companies_who_get_leads_v1($lead_detail) {
        return Company::select(
                                'companies.id',
                                'companies.company_name',
                                'companies.slug',
                                'm.file_name as logo',
                                )
                        ->leftJoin('membership_levels', 'companies.membership_level_id', 'membership_levels.id')
                        ->leftJoin('company_zipcodes', 'companies.id', 'company_zipcodes.company_id')
                        ->leftJoin('company_service_categories', 'companies.id', 'company_service_categories.company_id')
                        ->leftJoin('media as m', 'companies.company_logo_id', 'm.id')
                        ->with('company_lead_notification')
                        ->where([
                            ['membership_levels.paid_members', 'yes'],
                            ['membership_levels.lead_access', 'yes'],
                            ['membership_levels.slug', '!=', 'accredited-member'],
                            ['membership_levels.status', 'active'],
                            ['company_zipcodes.zip_code', $lead_detail->zipcode],
                            ['company_zipcodes.status', 'active'],
                            ['company_service_categories.service_category_id', $lead_detail->service_category_id],
                            ['company_service_categories.status', 'active'],
                        ])
                        ->leadStatus('active')
                        ->active()
                        //->inRandomOrder()
                        ->orderBy('companies.activated_at', 'ASC')
                        ->orderBy('companies.membership_level_id', 'ASC')
                        ->limit(3)
                        ->get();
    }

    /* Check  any company get lead start */

    /* Lead confirmation email to consumer start */



    /* Feedback status colors start */

    public static function feedback_status_color($feedback_status) {
        $statusColor = 'info';
        if ($feedback_status == 'Submitted') {
            $statusColor = 'info';
        } else if ($feedback_status == 'Confirmed') {
            $statusColor = 'primary';
        } else if ($feedback_status == 'Pre Approved') {
            $statusColor = 'secondary';
        } else if ($feedback_status == 'Member Approved') {
            $statusColor = 'purple';
        } else if ($feedback_status == 'Member Rejected') {
            $statusColor = 'warning';
        } else if ($feedback_status == 'Posted') {
            $statusColor = 'success';
        } else if ($feedback_status == 'Rejected') {
            $statusColor = 'danger';
        }

        return $statusColor;
    }

    /* Feedback status colors end */

    /* Complaint status colors start */

    public static function complaint_status_color($complaint_status) {
        $statusColor = 'info';
        if ($complaint_status == 'Submitted') {
            $statusColor = 'info';
        } else if ($complaint_status == 'Confirmed') {
            $statusColor = 'primary';
        } else if ($complaint_status == 'In Progress') {
            $statusColor = 'secondary';
        } else if ($complaint_status == 'Posted') {
            $statusColor = 'success';
        }

        return $statusColor;
    }

    /* Complaint status colors end */



    /* Company page screen shot start */

    public static function createCompanyPageScreenShot($companyObj) {
        $image_name = $companyObj->id . '-' . $companyObj->slug . '.jpg';
        $pathToImage = 'uploads' . DIRECTORY_SEPARATOR . 'company_page' . DIRECTORY_SEPARATOR . $image_name;

       Browsershot::url(url('/', ['company_slug' => $companyObj->slug]) . '?browsershot=yes')
                ->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->windowSize(1920, 2000)
                ->setScreenshotType('jpeg', 100)
                ->save(public_path($pathToImage));


        if (!is_null($companyObj->company_page_media_id)) {
            Media::where('id', $companyObj->company_page_media_id)->delete();
        }

        $mediaObj = Media::create([
                    'file_name' => $image_name,
                    'original_file_name' => $image_name,
                    'file_type' => 'image/jpeg',
                    'file_extension' => 'jpg',
        ]);

        $companyObj->company_page_media_id = $mediaObj->id;
        $companyObj->save();
    }

    /* Company page screen shot end */



    /* Networx API call start */

    public static function networxCall($lead) {
        // call networx API
        $tcpa_compliance_text = 'Clicking the submit request button constitutes your express written consent, without obligation to purchase, to be contacted by prospective service providers, Networx Systems, Inc. and its Trusted Partners (including with pre-recorded messages and through automated means, e.g. auto dialing and text messaging) via telephone, mobile device (including SMS and MMS), and/or email, even if your telephone number is on a corporate, state or the National Do Not Call Registry.';
        $networx_type = env('NETWORX_MODE');
        $networx_user_id = env('NETWORX_USER_ID');
        $networx_access_key = env('NETWORX_ACCESS_KEY');

        $pass_data = 'nx_userId=' . $networx_user_id . '&nx_access_key=' . $networx_access_key;
        $pass_data .= '&task_id=' . $lead->service_category->networx_task_id;
        $pass_data .= '&zipcode=' . (($networx_type == 'sandbox') ? '00001' : $lead->zipcode);
        $full_name = explode(" ", $lead->full_name);
        if (isset($full_name[1]) && $full_name[1] != '') {
            $pass_data .= '&f_name=' . $full_name[0] . '&l_name=' . $full_name[1];
        } else {
            $pass_data .= '&f_name=' . $full_name[0] . '&l_name=' . $full_name[0];
        }

        $pass_data .= '&phone=' . trim(str_replace('-', '', preg_replace('/[^A-Za-z0-9\-]/', '', $lead->phone)));
        $pass_data .= '&email=' . $lead->email;
        $pass_data .= '&comments=' . trim(str_replace('-', '', preg_replace('/[^A-Za-z0-9\-]/', '', $lead->timeframe)));
        $pass_data .= '&cert_url=' . urlencode($lead->cert_url);
        $pass_data .= '&tcpa_compliance_text=' . urlencode($tcpa_compliance_text);
        $url = "https://api.networx.com?" . $pass_data;

        Log::channel('custom_db')->info('Networx parsing', [
            'data' => $url,
            'key_identifier' =>   $lead->correlation_id,
            'key_identifier_type' => KeyIdentifierType::GeneralLead
        ]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //  curl_setopt($ch,CURLOPT_HEADER, false);

        $output = curl_exec($ch);
        curl_close($ch);

        $response = simplexml_load_string($output);
        $con = json_encode($response);
        $newArr = json_decode($con, true);

        //dd($newArr);
        return $newArr;

        /* if ($newArr['statusCode'] == '200') {
          return $newArr['successCode'];
          } else if ($newArr['statusCode'] == '400') {
          return '';
          } */
    }

    /* Networx API call end */


    /* Clean HTML DATA from formatting */

    public static function cleanHtml($str) {

        $tags = ['font', 'a'];
        $replace_with = '';

        foreach ($tags as $tag) {
            $str = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/", $replace_with, $str);
        }

        $str = self::removeTag($str, 'style');
        $str = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $str);
        return $str;
    }

    public static function removeTag($str, $tag) {
        $str = preg_replace("#\\<" . $tag . "(.*)/" . $tag . ">#iUs", "", $str);
        return $str;
    }

    public static function strip_tags_content($text, $tags = '', $invert = FALSE) {

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) and count($tags) > 0) {
            if ($invert == FALSE) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == FALSE) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

    /* Clean HTML DATA from formatting */


    /* Vimeo video image get start */

    public static function get_vimeo_thumb($videoid, $size = "large", $return = false) {
        $imgid = $videoid;
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
        $the_thumb = "";

        switch ($size) {
            case 'small':
                $the_thumb = $hash[0]['thumbnail_small'];
                break;
            case 'medium':
                $the_thumb = $hash[0]['thumbnail_medium'];
                break;
            case 'large':
                $the_thumb = $hash[0]['thumbnail_large'];
                break;
        }

        if (!$return) {
            return $the_thumb;
        } else {
            return $the_thumb;
        }
    }

    /* Vimeo video image get end */

    /*format phone number*/
    public static function formatPhoneNumber($phoneNumber) {
        $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

        if(strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);

            $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
        }
        else if(strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree.'-'.$lastFour;
        }

        return $phoneNumber;
    }

    public static function getFullDomain($slug)
    {
        switch ($slug) {
            case self::DOMAIN_SLUG_TP:
                return 'TrustPatrick';
            case self::DOMAIN_SLUG_AAD:
                return 'AllAboutDriveways';
            default:
                return null;
        }
    }
}

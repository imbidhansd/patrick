<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\consumer\ConsumerMail;
use Illuminate\Support\Facades\Mail;
use View;
use Validator;
use Str;
use App\Models\Custom;
use App\Models\State;
use App\Models\Trade;
use App\Models\ServiceCategoryType;
use App\Models\NonMemberZipcode;
use App\Models\NonMemberTopLevelCategory;
use Rap2hpoutre\FastExcel\FastExcel;

class NonMemberController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order' || $segment == 'import') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        //Import members
        $this->urls += ["import" => url("admin/" . $url_key . "/import")];
        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name . ' Company');
        $this->module_plural_name = Str::plural($module_display_name . ' Company');

        $how_did_you_hear_about_us = [
            'Radio',
            'Youtube Show Consumers Corner',
            'Online Search Engine',
            'TV',
            'Customer'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'states' => State::active()->order()->pluck('name', 'id'),
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'service_category_types' => ServiceCategoryType::where('id', '!=', '3')->active()->order()->pluck('title', 'id'),
            'how_did_you_hear_about_us' => array_combine($how_did_you_hear_about_us, $how_did_you_hear_about_us),
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }
        
        $top_level_categories = [];
        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['non_members.trade_id']) && $requestArr['non_members.trade_id'] > 0) {
                $top_level_categories = \App\Models\TopLevelCategory::leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                        ->where('top_level_category_trades.trade_id', $requestArr['non_members.trade_id'])
                        ->pluck('top_level_categories.title', 'top_level_categories.id');
            }
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'non_members.trade_id' => [
                    'title' => 'Trade',
                    'options' => $this->common_data['trades'],
                    'id' => 'trade_id'
                ],
                'non_member_top_level_categories.top_level_category_id' => [
                    'title' => 'Top Level Category',
                    'options' => $top_level_categories,
                    'id' => 'top_level_categories'
                ],
                'non_members.service_category_type_id' => [
                    'title' => 'Service Category Type',
                    'options' => $this->common_data['service_category_types'],
                ],
            ],
            'enable_import' => 1
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'company_name' => 'required',
                    'email' => 'required|unique:non_members,email',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state_id' => 'required',
                    'zipcode' => 'required',
                    'trade_id' => 'required',
                    'top_level_categories' => 'required',
                    'how_did_you_hear_about_us' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['activation_date'] = now()->format(env('DB_DATE_FORMAT'));
            $requestArr['status'] = 'active';
            
            if (isset($requestArr['top_level_categories']) && count($requestArr['top_level_categories']) > 0) {
                $top_level_categories = $requestArr['top_level_categories'];
                $requestArr['top_level_categories'] = implode(",", $requestArr['top_level_categories']);
            }
            
            if ($requestArr['service_category_type_id'] == 'both') {
                $requestArr['service_category_type_id'] = null;
            }
            
            $itemObj = $this->modelObj->create($requestArr);

            if (isset($top_level_categories) && count($top_level_categories) > 0) {
                foreach ($top_level_categories AS $top_level_category_id) {
                    $insertArr = [
                        'non_member_id' => $itemObj->id,
                        'top_level_category_id' => $top_level_category_id
                    ];
                    NonMemberTopLevelCategory::create($insertArr);
                }
            }
            
            if (isset($requestArr['zipcode']) && $requestArr['zipcode'] != '' && isset($requestArr['mile_range']) && $requestArr['mile_range'] != '') {
                try {
                    $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);
                    if (count($zipCodes) > 0) {
                        foreach ($zipCodes as $zipcode_item) {
                            $stateObj = State::where('short_name', $zipcode_item['state'])->first();
                            $insertZipcodeArr = [
                                'non_member_id' => $itemObj->id,
                                'zipcode' => $zipcode_item['zip_code'],
                                'distance' => $zipcode_item['distance'],
                                'city' => $zipcode_item['city'],
                                'state' => $zipcode_item['state'],
                                'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                            ];

                            NonMemberZipcode::create($insertZipcodeArr);
                        }
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }

            /* Registration confirmation success mail to Company */
            $mail_id = "117"; /* Mail title: Register Confirmation Success Email */
            $replaceArr = [
                'company_name' => $itemObj->company_name,
            ];
            Mail::to($itemObj->email)->send(new ConsumerMail($mail_id, $replaceArr));

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function import(Request $request) {
        if ($request->hasFile('nonmemberfile')) {
            $file = $request->file('nonmemberfile');   
            $import = (new FastExcel)->import($file, function ($line) {              
                //validation for required fields
                $mileRange = $line['Mile Range'];
                $typeOfServiceProvider = $line['Type Of Service Provider'];
                $typeOfServiceProvider = $line['Type Of Service Provider'];
                $serviceOffered = $line['Service Offered'];
                $serviceType = $line['Service Type'];                
                $stateObj = State::where('short_name', $line['stateCode'])->first();
                $insertArr = [
                    'first_name' => $line['First Name'],
                    'last_name' => $line['Last Name'],
                    'company_name' => $line['Company Name'],
                    'email' => $line['Email'],
                    'phone' => $line['Phone'],
                    'address' => $line['Address'],
                    'city' => $line['City'],
                    'state_id' => $stateObj->id,
                    'zipcode' => $line['Zipcode'],
                    'trade_id' => '',
                    'top_level_categories' => '',
                    'how_did_you_hear_about_us' => $line['How Did You Hear About Us'],
                    'comments' => $line['Comments'],
                    'activation_date' => now()->format(env('DB_DATE_FORMAT')),
                    'status' => 'active'
                ];                
                dd($insertArr);
                //NonMember::create($insertArr);
            });
            dd($import );
        }

        return redirect($this->urls['list']);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'company_name' => 'required',
                    'email' => 'required|unique:non_members,email,' . $id . ',id',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state_id' => 'required',
                    'zipcode' => 'required',
                    'trade_id' => 'required',
                    'top_level_categories' => 'required',
                    'how_did_you_hear_about_us' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            
            if (isset($requestArr['top_level_categories']) && count($requestArr['top_level_categories']) > 0){
                $top_level_categories = $requestArr['top_level_categories'];
                $requestArr['top_level_categories'] = implode(",", $requestArr['top_level_categories']);
            }
            
            if ($requestArr['service_category_type_id'] == 'both') {
                $requestArr['service_category_type_id'] = null;
            }
            
            $itemObj->update($requestArr);

            if (isset($top_level_categories) && count($top_level_categories) > 0) {
                NonMemberTopLevelCategory::where('non_member_id', $itemObj->id)->delete();
                foreach ($top_level_categories AS $top_level_category_id) {
                    $insertArr = [
                        'non_member_id' => $itemObj->id,
                        'top_level_category_id' => $top_level_category_id
                    ];

                    NonMemberTopLevelCategory::create($insertArr);
                }
            }
            
            if (isset($requestArr['zipcode']) && $requestArr['zipcode'] != '' && isset($requestArr['mile_range']) && $requestArr['mile_range'] != '') {
                try {
                    $zipCodes = Custom::getZipCodeRange($requestArr['zipcode'], $requestArr['mile_range']);
                    if (count($zipCodes) > 0) {
                        NonMemberZipcode::where('non_member_id', $itemObj->id)->delete();
                        
                        foreach ($zipCodes as $zipcode_item) {
                            $stateObj = State::where('short_name', $zipcode_item['state'])->first();
                            $insertZipcodeArr = [
                                'non_member_id' => $itemObj->id,
                                'zipcode' => $zipcode_item['zip_code'],
                                'distance' => $zipcode_item['distance'],
                                'city' => $zipcode_item['city'],
                                'state' => $zipcode_item['state'],
                                'state_id' => ((!is_null($stateObj)) ? $stateObj->id : null),
                            ];

                            NonMemberZipcode::create($insertZipcodeArr);
                        }
                    }
                } catch (Exception $e) {
                    return 'fail';
                }
            }

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;

        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->warning();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

}

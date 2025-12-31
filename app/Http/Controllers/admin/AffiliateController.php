<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateMainCategory;
use App\Models\Aweber;
use App\Models\Custom;
use App\Models\ServiceCategoryType;
// Models [start]
use App\Models\TopLevelCategory;
use App\Models\Trade;
use Illuminate\Http\Request;
use Str;
use Validator;
use View;

class AffiliateController extends Controller
{

    public function __construct()
    {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);
        $this->urls['configure'] = $url_key . '.configure'; //url("admin/" . $url_key . "/configure");
        $this->urls['configuration_form_file'] = 'admin.' . $url_key . '.configuration_form';
        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

        //Post Types
        $this->post_type = $url_key;

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);
        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'service_category_types' => ServiceCategoryType::where('id', '!=', '3')->active()->order()->pluck('title', 'id'),
            'top_level_categories' => TopLevelCategory::orderBy('title', 'ASC')->pluck('title', 'id'),
        ];
        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request)
    {
        $list_params = Custom::getListParams($request);

        $admin_page_title = 'Manage ' . $this->module_plural_name;

        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
            ],
        ];
        return view($this->view_base . '.index', $data);
    }

    public function create()
    {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->request->add(['trade_id' => '1']);
        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'api_secret' => 'required',
            'affiliate_name' => 'required',
            'domain' => 'required',
            'domain_abbr' => 'required',
            'trade_id' => 'required',
            'main_categories' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                ->withErrors($validator)
                ->withInput();
        } else {
            $requestArr = $request->all();
            if (isset($requestArr['main_categories']) && count($requestArr['main_categories']) > 0) {
                $main_categories = $requestArr['main_categories'];
            }

            if ($requestArr['service_category_type_id'] == 'both') {
                $requestArr['service_category_type_id'] = null;
            }
            $createdRecord = $this->modelObj->create($requestArr);
            if (isset($main_categories) && count($main_categories) > 0) {
                foreach ($main_categories as $main_category_id) {
                    $parts = explode('-', $main_category_id);
                    $main_category_id = $parts[0]; // Contains '69'
                    $service_category_type_id = $parts[1]; // Contains '1'

                    $insertArr = [
                        'affiliate_id' => $createdRecord->id,
                        'main_category_id' => $main_category_id,
                        'service_category_type_id' => $service_category_type_id
                    ];
                    AffiliateMainCategory::create($insertArr);
                }
            }
            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id)
    {
        $formObj = $this->modelObj->findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request)
    {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'api_secret' => 'required',
            'affiliate_name' => 'required',
            'domain' => 'required',
            'domain_abbr' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                ->withErrors($validator)
                ->withInput();
        } else {
            $requestArr = $request->all();
            if (isset($requestArr['main_categories']) && count($requestArr['main_categories']) > 0) {
                $main_categories = $requestArr['main_categories'];
            }

            if ($requestArr['service_category_type_id'] == 'both') {
                $requestArr['service_category_type_id'] = null;
            }
            $itemObj->update($requestArr);

            if (isset($main_categories) && count($main_categories) > 0) {
                AffiliateMainCategory::where('affiliate_id', $itemObj->id)->delete();
                foreach ($main_categories as $main_category_id) {
                    $parts = explode('-', $main_category_id);
                    $main_category_id = $parts[0]; // Contains '69'
                    $service_category_type_id = $parts[1]; // Contains '1'

                    $insertArr = [
                        'affiliate_id' => $itemObj->id,
                        'main_category_id' => $main_category_id,
                        'service_category_type_id' => $service_category_type_id
                    ];

                    AffiliateMainCategory::create($insertArr);
                }
            }
            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function configure($id)
    {
        $formObj = $this->modelObj->findOrFail($id);
        $data = ['admin_page_title' => 'Configure ' . $this->singular_display_name];
        $data['formObj'] = $formObj;
        $data['aweberLists'] = [];
        if ($formObj->aweber_enabled && !empty($formObj->aweber_account_id) && !empty($formObj->aweber_refresh_token)) {
            $list = Aweber::GetLists($formObj->aweber_account_id, $formObj->aweber_refresh_token);
            $data['aweberLists'] = $list;
        }

        return view($this->view_base . '.configure', $data);
    }

    public function store_configuration($affiliate, Request $request)
    {
        $itemObj = $this->modelObj->findOrFail($affiliate);       
        $requestArr = $request->all();
        $itemObj->aweber_member_list = $requestArr["aweber_member_request_list"] ?? null;
        if ($itemObj->aweber_enabled) {
            foreach ($itemObj->main_category_list as $main_category) {
                $main_category->aweber_member_listname = $requestArr["aweber_member_list_" . $main_category->id] ?? null;
                $main_category->aweber_non_member_listname = $requestArr["aweber_non_member_list_" . $main_category->id] ?? null;
                $main_category->save();
            }

            $itemObj->update($requestArr);
            flash($this->module_messages['update'])->success();
        }

        return redirect(route($this->urls['configure'], ['affiliate' => $affiliate]));
    }

    public function destroy(Request $request, $id)
    {
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\VideoPodcastCompany;
use App\Models\Company;
use App\Models\Trade;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;

class VideoPodcastController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

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
            'service_category_type' => ServiceCategoryType::active()->order()->pluck('title', 'id'),
            'top_level_category' => TopLevelCategory::active()->order()->pluck('title', 'id'),
            'main_category' => MainCategory::active()->order()->pluck('title', 'id')
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'video_podcasts.trade_id' => [
                    'title' => 'Trade',
                    'options' => $this->common_data['trades'],
                    'id' => 'trade_id'
                ],
                'video_podcasts.service_category_type_id' => [
                    'title' => 'Service Category Type',
                    'options' => $this->common_data['service_category_type'],
                ],
                'video_podcasts.top_level_category_id' => [
                    'title' => 'Top Level Category',
                    'options' => $this->common_data['top_level_category'],
                    'id' => 'top_level_category_id'
                ],
                'video_podcasts.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $this->common_data['main_category'],
                    'id' => 'main_category_id'
                ],
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];

        $data['trades'] = $this->common_data['trades'];
        $data['service_category_type'] = $this->common_data['service_category_type'];
        $data['top_level_category'] = $this->common_data['top_level_category'];
        $data['main_category'] = $this->common_data['main_category'];
        $data['companies'] = Company::order()->pluck('company_name', 'id');

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj = $this->modelObj->create($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
            }


            if (isset($requestArr['company_id']) && count($requestArr['company_id']) > 0) {
                foreach ($requestArr['company_id'] AS $company_item) {
                    $insertArr = [
                        'video_podcast_id' => $itemObj->id,
                        'company_id' => $company_item
                    ];

                    VideoPodcastCompany::create($insertArr);
                }
            }

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;

        $data['trades'] = $this->common_data['trades'];
        $data['service_category_type'] = $this->common_data['service_category_type'];
        $data['top_level_category'] = $this->common_data['top_level_category'];
        $data['main_category'] = $this->common_data['main_category'];

        $data['companies'] = Company::order()->pluck('company_name', 'id');
        $data['selected_companies'] = VideoPodcastCompany::where('video_podcast_id', $formObj->id)->pluck('company_id');

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
            }

            if (isset($requestArr['company_id']) && count($requestArr['company_id']) > 0) {
                VideoPodcastCompany::where('video_podcast_id', $itemObj->id)->delete();

                foreach ($requestArr['company_id'] AS $company_item) {
                    $insertArr = [
                        'video_podcast_id' => $itemObj->id,
                        'company_id' => $company_item
                    ];

                    VideoPodcastCompany::create($insertArr);
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

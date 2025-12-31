<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\ServiceCategoryType;
use App\Models\NetworxTask;

class ServiceCategoryController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        // Common Model
        if ($module_display_name != '') {
            $model_name = '\\App\\Models\\' . str_replace(' ', '', $module_display_name);
            $this->modelObj = new $model_name;
        }

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
            'top_level_categories' => TopLevelCategory::orderBy('title', 'ASC')->pluck('title', 'id'),
            'service_category_types' => ServiceCategoryType::pluck('title', 'id'),
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

        $main_categories = null;

        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['service_categories.top_level_category_id']) && $requestArr['service_categories.top_level_category_id'] > 0) {
                $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                        ->where('main_category_top_level_categories.top_level_category_id', $requestArr['service_categories.top_level_category_id'])
                        ->pluck('main_categories.title', 'main_categories.id');
            }
        }
        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'search' => [
                'service_categories.top_level_category_id' => [
                    'id' => 'top_level_category_id',
                    'title' => 'Top Level Category',
                    'options' => $this->common_data['top_level_categories'],
                ],
                'service_categories.main_category_id' => [
                    'id' => 'main_category_id',
                    'title' => 'Main Category',
                    'options' => $main_categories,
                ],
                'service_categories.service_category_type_id' => [
                    'id' => 'service_category_type_id',
                    'title' => 'Service Category Type',
                    'options' => ServiceCategoryType::order()->pluck('title', 'id'),
                ],
            ],
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        $data['service_category_main_categories'] = [];
        $data['main_categories'] = [];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'top_level_category_id' => 'required|exists:top_level_categories,id',
                    'main_category_id' => 'required|exists:main_categories,id',
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj = $this->modelObj->create($requestArr);

            //Generate service category id
            $service_category_id = $this->modelObj->generateServiceCategoryId($itemObj);

            $itemObj->service_category_id = $service_category_id;
            $itemObj->sc_code = $service_category_id;
            $itemObj->save();

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $model = $this->modelObj;
        $formObj = $model::findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        $data['main_categories'] = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                ->where('main_category_top_level_categories.top_level_category_id', $formObj->top_level_category_id)
                ->pluck('main_categories.title', 'main_categories.id');

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'top_level_category_id' => 'required|exists:top_level_categories,id',
                    'main_category_id' => 'required|exists:main_categories,id',
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            //Generate service category id
            $service_category_id = $this->modelObj->generateServiceCategoryId($itemObj);
            $itemObj->service_category_id = $service_category_id;
            $itemObj->sc_code = $service_category_id;
            $itemObj->save();

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function update_service_category_id(Request $request) {
        $validator = Validator::make($request->all(), [
                    'category_id' => 'required',
                    'service_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();

            // check another id is same or not
            $chkServiceCategory = $this->modelObj->where([
                        ['service_category_id', $requestArr['service_category_id']],
                        ['id', '!=', $requestArr['category_id']]
                    ])->first();

            if (!is_null($chkServiceCategory)) {
                flash("Service category id already used.")->error();
                return back();
            } else {
                $this->modelObj->where('id', $requestArr['category_id'])
                        ->update([
                            'service_category_id' => $requestArr['service_category_id'],
                            'sc_code' => $requestArr['service_category_id']
                            ]
                    );


                flash("Service category id updated successfully.")->success();
                return back();
            }
        }
    }

    public function update_networx_details(Request $request) {
        $validator = Validator::make($request->all(), [
                    'category_id' => 'required',
                    'networx_task_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                foreach ($messages AS $message_item) {
                    $errorMessage .= $message_item . '<br />';
                }
            }

            if ($request->has('ajax_form') && $request->get('ajax_form') == 'yes') {
                return [
                    'success' => 0,
                    'title' => 'Warning',
                    'type' => 'warning',
                    'message' => $errorMessage
                ];
            } else {
                return back()->withErrors($validator)->withInput();
            }
        } else {
            $requestArr = $request->all();
            $networx_detail = NetworxTask::where('task_id', $requestArr['networx_task_id'])->first();

            if (is_null($networx_detail)) {
                if (isset($requestArr['ajax_form']) && $requestArr['ajax_form'] != '') {
                    return [
                        'success' => 0,
                        'title' => 'Warning',
                        'type' => 'warning',
                        'message' => 'Networx Task ID not found.'
                    ];
                } else {
                    flash('Networx Task ID not found.')->warning();
                    return back();
                }
            }

            $this->modelObj->where('id', $requestArr['category_id'])->update([
                'networx_id' => $networx_detail->id,
                'networx_task_id' => $networx_detail->task_id,
            ]);

            if (isset($requestArr['ajax_form']) && $requestArr['ajax_form'] != '') {
                return [
                    'success' => 1,
                    'title' => 'Success',
                    'type' => 'success',
                    'message' => "Networx detail in service category updated successfully."
                ];
            } else {
                flash("Networx detail in service category updated successfully.")->success();
                return back();
            }
        }
    }

    private function add_main_category_items($service_category_id, $main_categories = []) {
        $del_query = ServiceCategoryMainCategory::where('service_category_id', $service_category_id);
        if (!is_null($main_categories) && is_array($main_categories)) {
            $del_query->whereNotIn('main_category_id', $main_categories);
        }
        $del_query->delete();

        if (!is_null($main_categories) && is_array($main_categories)) {
            foreach ($main_categories as $main_category_id) {
                ServiceCategoryMainCategory::firstOrCreate([
                    'service_category_id' => $service_category_id,
                    'main_category_id' => $main_category_id,
                ]);
            }
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

    public function reorder($top_level_category_id, $main_category_id, $service_category_type_id, Request $request) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        $data['service_category_type_id'] = $service_category_type_id;
        if (!is_null($top_level_category_id)) {
            $data['main_categories'] = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->where('main_category_top_level_categories.top_level_category_id', $top_level_category_id)
                    ->pluck('main_categories.title', 'main_categories.id');
        }

        /* if (!is_null($main_category_id)) {
          $data['item_list'] = $this->modelObj::where('main_category_id', $main_category_id)->orderBy('sort_order', 'ASC')->get();
          } */

        if (!is_null($service_category_type_id)) {
            $data['item_list'] = $this->modelObj->where([
                        ['top_level_category_id', $top_level_category_id],
                        ['main_category_id', $main_category_id],
                        ['service_category_type_id', $service_category_type_id],
                    ])
                    ->orderBy('sort_order', 'ASC')
                    ->get();
        }

        $data['top_level_category_id'] = $top_level_category_id;
        $data['main_category_id'] = $main_category_id;
        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                $this->modelObj::where('id', $item)->update(['sort_order' => $counter++]);
            }
        }
    }

    public function getOptions(Request $request) {
        // Get All Service Categories
        $data['options'] = $this->modelObj->where('main_category_id', $request->get('main_category_id'))
                ->select('title', 'id')
                ->orderBy('title', 'asc')
                ->get();

        return view($this->view_base . '._get_options', $data);
    }

    public function getServiceOptions(Request $request) {
        // Get All Service Categories
        $data['options'] = $this->modelObj->where(function ($q) use ($request) {
                    if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '') {
                        $q->where('top_level_category_id', $request->get('top_level_category_id'));
                    }

                    if ($request->has('service_category_type_id') && $request->get('service_category_type_id') != '') {
                        $q->where('service_category_type_id', $request->get('service_category_type_id'));
                    }

                    if ($request->has('main_category_id') && $request->get('main_category_id') != '') {
                        $q->where('main_category_id', $request->get('main_category_id'));
                    }
                })
                ->select('title', 'id')
                ->orderBy('title', 'asc')
                ->get();

        return view($this->view_base . '._get_options', $data);
    }

    /* Networx List */

    public function networx_task_list(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = $this->modelObj->getAdminNetworxList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $main_categories = null;

        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['service_categories.top_level_category_id']) && $requestArr['service_categories.top_level_category_id'] > 0) {
                $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                        ->where('main_category_top_level_categories.top_level_category_id', $requestArr['service_categories.top_level_category_id'])
                        ->pluck('main_categories.title', 'main_categories.id');
            }
        }

        $this->singular_display_name = 'Networx task';
        $this->module_plural_name = 'Networx task list';

        $data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'admin_page_title' => 'Manage ' . $this->module_plural_name,
            'rows' => $rows,
            'list_params' => $list_params,
            'module_urls' => [
                'list' => 'networx_task_list',
                'reorder' => ''
            ],
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'search' => [
                'service_categories.top_level_category_id' => [
                    'id' => 'top_level_category_id',
                    'title' => 'Top Level Category',
                    'options' => $this->common_data['top_level_categories'],
                ],
                'service_categories.main_category_id' => [
                    'id' => 'main_category_id',
                    'title' => 'Main Category',
                    'options' => $main_categories,
                ],
                'service_categories.service_category_type_id' => [
                    'id' => 'service_category_type_id',
                    'title' => 'Service Category Type',
                    'options' => ServiceCategoryType::order()->pluck('title', 'id'),
                ],
            ],
        ];

        //dd($data);
        return view($this->view_base . '.networx_list', $data);
    }

}

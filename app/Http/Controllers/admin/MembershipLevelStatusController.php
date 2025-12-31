<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\MembershipLevel;
use App\Models\MembershipStatus;

class MembershipLevelStatusController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        $this->urls = Custom::getModuleUrls($url_key);
        //dd(route('top_level_categories.edit', ['id' => 1]));
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
            'membership_levels' => MembershipLevel::active()->order()->pluck('title', 'id'),
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

        $membership_status = [];
        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['membership_level_statuses.membership_level_id']) && $requestArr['membership_level_statuses.membership_level_id'] > 0) {
                $membership_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                        ->where('membership_level_statuses.membership_level_id', $requestArr['membership_level_statuses.membership_level_id'])
                        ->active()
                        ->orderBy('membership_statuses.title', 'ASC')
                        ->pluck('membership_statuses.title', 'membership_statuses.id');
            }
        }

        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'search' => [
                'membership_level_statuses.membership_level_id' => [
                    'title' => 'Membership Level',
                    'options' => $this->common_data['membership_levels'],
                    'id' => 'membership_level_id'
                ],
                'membership_level_statuses.membership_status_id' => [
                    'title' => 'Membership Status',
                    'options' => $membership_status,
                    'id' => 'membership_status_id'
                ],
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data['admin_page_title'] = 'Create ' . $this->singular_display_name;
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'membership_level_id' => 'required',
                    'membership_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])->withErrors($validator)->withInput();
        } else {
            $requestArr = $request->all();
            $checkRecordAvailable = $this->modelObj->where('membership_level_id', $requestArr['membership_level_id'])->where('membership_status_id', $requestArr['membership_status_id'])->first();

            if (!is_null($checkRecordAvailable)) {
                $checkRecordAvailable->update($requestArr);
            } else {
                $this->modelObj->create($requestArr);
            }

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'membership_level_id' => 'required',
                    'membership_status_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;

        try {
            $modelObj->update(['video_id' => null, 'video_title' => null]);

            //$modelObj->delete();

            flash($this->module_messages['delete'])->success();
            return back();
        } catch (Exception $e) {

            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function updateStatus(Request $request) {
        if (in_array($request->get('action'), ['delete'])) {
            $this->modelObj->whereIn('id', $request->get('ids'))->update(['video_id' => null, 'video_title' => null]);

            flash($this->module_messages['delete'])->success();
        } else {
            flash($this->module_messages['delete_error'])->danger();
        }

        return back();
    }

}

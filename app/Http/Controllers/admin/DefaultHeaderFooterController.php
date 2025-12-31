<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;

class DefaultHeaderFooterController extends Controller {

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
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'content_type' => 'required',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj = $this->modelObj->create($requestArr);

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

    public function update(Request $request, $id) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'content_type' => 'required',
                    'content' => 'required',
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

    public function destroy($id) {
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

    public function get_header_template(Request $request) {
        if ($request->has('id') && $request->get('id') != '') {
            $emailObj = $this->modelObj::emailtype('header')->find($request->get('id'));

            if (!is_null($emailObj)) {
                return [
                    'success' => 1,
                    'text' => $emailObj->content,
                ];
            } else {
                return [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'text' => 'Header template selection not found.',
                ];
            }
        } else {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'text' => 'Header template selection not found.',
            ];
        }
    }

    public function get_footer_template(Request $request) {
        if ($request->has('id') && $request->get('id') != '') {
            $emailObj = $this->modelObj::emailtype('footer')->find($request->get('id'));

            if (!is_null($emailObj)) {
                return [
                    'success' => 1,
                    'text' => $emailObj->content,
                ];
            } else {
                return [
                    'success' => 0,
                    'title' => 'Error',
                    'type' => 'error',
                    'text' => 'Footer template selection not found.',
                ];
            }
        } else {
            return [
                'success' => 0,
                'title' => 'Error',
                'type' => 'error',
                'text' => 'Footer template selection not found.',
            ];
        }
    }

}

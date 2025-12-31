<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Email;
use App\Models\DefaultEmail;

class EmailController extends Controller {

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

        $email_for = [
            'Site User',
            'Company',
            'Admin'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'email_for' => array_combine($email_for, $email_for),
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
                'emails.email_for' => [
                    'title' => 'Email For',
                    'options' => $this->common_data['email_for']
                ]
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        $data['default_email'] = DefaultEmail::active()->order()->first();

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {

        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['send_time'] = "";
            if (isset($requestArr['sendtime']) && $requestArr['sendtime'] != '') {
                $requestArr['send_time'] .= $requestArr['sendtime'];
            }

            if (isset($requestArr['sendtime_selection']) && $requestArr['sendtime_selection'] != '') {
                $requestArr['send_time'] .= ' ' . $requestArr['sendtime_selection'];
            }
            $model = $this->modelObj;
            $itemObj = $model::create($requestArr);

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $model = $this->modelObj;
        $formObj = $model::findOrFail($id);

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        if ($formObj->send_time != '') {
            $send_time = explode(' ', $formObj->send_time);
            $formObj->sendtime = $send_time[0];
            $formObj->sendtime_selection = $send_time[1];
        }
        $data['formObj'] = $formObj;

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $model = $this->modelObj;
        $itemObj = $model::findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['send_time'] = "";
            if (isset($requestArr['sendtime']) && $requestArr['sendtime'] != '') {
                $requestArr['send_time'] .= $requestArr['sendtime'];
            }

            if (isset($requestArr['sendtime_selection']) && $requestArr['sendtime_selection'] != '') {
                $requestArr['send_time'] .= ' ' . $requestArr['sendtime_selection'];
            }
            $itemObj->update($requestArr);

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy(Request $request, $id) {
        $model = $this->modelObj;
        $modelObj = $model::findOrFail($id);
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

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Company;
use App\Models\State;

class CompanyOwnerController extends Controller {

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

        // Post type
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
            'companies' => Company::order()->pluck('company_name', 'id'),
            'states' => State::order()->pluck('name', 'id')
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index($company_id = '', Request $request) {
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        $list_params = Custom::getListParams($request);
        if ($company_id != ''){
            $list_params['company_id'] = $company_id;
        }
        
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
                'company_users.company_id' => [
                    'title' => 'Company',
                    'options' => $this->common_data['companies'],
                    'id' => 'company_id'
                ]
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'company_user_type' => 'required',
                    'username' => 'required|unique:company_users,username,' . $id . ',id'
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            if ($requestArr['company_user_type'] == 'company_super_admin') {
                $this->modelObj->where('company_id', $itemObj->company_id)->update(['company_user_type' => 'company_admin']);
            }
            $itemObj->update($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
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

    /* Change Password */

    public function changeUserPassword(Request $request) {
        $user = $this->modelObj->find($request->get('user_id'));
        if ($user) {
            $user->password = bcrypt($request->get('new_password'));
            $user->save();

            return ['status' => 1, 'message' => 'Password has been changed'];
        } else {
            return ['status' => 0, 'message' => 'User not found'];
        }
    }

}

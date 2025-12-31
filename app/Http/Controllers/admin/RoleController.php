<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use \Carbon\Carbon;
use Str;
use Arr;
use DB;
// Spatie Role
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// Models [start]
use App\Models\Custom;
use App\Models\ModuleCategory;

class RoleController extends Controller {

    //
    public function __construct() {
        $url_key = 'roles';
        $module_display_name = 'Role';

        // Links
        $this->urls = Custom::getModuleUrls($url_key);

        $this->modelObj = new Role;

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        View::share([
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
        ]);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $rows = Role::paginate(20);

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
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
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
        $data['formObj'] = $formObj;
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $model = $this->modelObj;
        $itemObj = $model::findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:roles,name,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $row->id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            /* $permissions = [
              'create post',
              'edit post',
              'delete post',
              ];

              $itemObj->syncPermissions($permissions); */

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

    public function getPermissions($id, Request $request) {

        $role = Role::findOrFail($id);

        $current_permissions = DB::table('role_has_permissions')->where('role_id', $id)->pluck('permission_id');

        $data = [
            'admin_page_title' => 'Set Permission For ' . $role->name,
            'role' => $role,
            'module_categories' => ModuleCategory::with(['modules.permissions'])->order()->get(),
            'current_permissions' => !is_null($current_permissions) ? $current_permissions->toArray() : []
        ];

        return view($this->view_base . '.permissions', $data);
    }

    public function postPermissions($id, Request $request) {
        $role = Role::findOrFail($id);

        $permissions = [];
        if ($request->has('permissions') && $request->get('permissions') > 0) {
            $permissions = Permission::whereIn('id', $request->get('permissions'))->get();
        }
        $role->syncPermissions($permissions);

        flash('Permission has been set for ' . $role->name . ' role')->success();
        return redirect($this->urls['list']);
    }

}

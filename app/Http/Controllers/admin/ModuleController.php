<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\ModuleCategory;
use Spatie\Permission\Models\Permission;

class ModuleController extends Controller {

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
            'module_categories' => ModuleCategory::order()->pluck('title', 'id'),
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
            'search' => [
                'modules.module_category_id' => [
                    'title' => 'Category',
                    'options' => $this->common_data['module_categories'],
                ],
            ],
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name,
            'permissions' => []
        ];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
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
            // Create Permissions
            $this->create_permissions($requestArr, $itemObj);
            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
            'permissions' => $formObj->permissions,
        ];
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $row->id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);
            // Create Permissions
            $this->create_permissions($requestArr, $itemObj);
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

    // Create New Permissions [start]
    private function create_permissions($requestArr, $itemObj) {
        $permission_options = [];
        if ($requestArr['permissions'] != '') {

            $permissions_arr = explode(',', $requestArr['permissions']);
            if (is_array($permissions_arr) && count($permissions_arr) > 0) {
                $insertedPermissions = [];
                foreach ($permissions_arr as $permission) {
                    $permission_name = $itemObj->name . '.' . Str::slug($permission);
                    $permission_options[] = $permission_name;
                    $permissionObj = Permission::firstOrCreate(['module_id' => $itemObj->id, 'name' => $permission_name, 'title' => Str::title($permission)]);

                    $insertedPermissions[] = $permissionObj->id;
                }
                Permission::where('module_id', $itemObj->id)->whereNotIn('id', $insertedPermissions)->delete();
            }
        }
        $itemObj->permission_options = json_encode($permission_options);
        $itemObj->save();
    }

    public function reorder($module_category_id = null) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        $item_list = $this->modelObj->order();
        
        if ($module_category_id != '') {
            $item_list->where('module_category_id', $module_category_id);
        }

        $data['module_category_id'] = $module_category_id;
        $data['item_list'] = $item_list->get();

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

    // Create New Permissions [end]
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use \Carbon\Carbon;
use Str;
use Arr;
// Models [start]
use App\Models\Custom;

class BasicController extends Controller {

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
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $requestArr = $request->all();
            $requestArr['post_type'] = strtolower($this->singular_display_name);

            $model = $this->modelObj;
            $itemObj = $model::create($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'post');
                $itemObj->media_id = $imageArr['mediaObj']->id;
            }
            if ($request->hasFile('banner')) {
                $imageArr = Custom::uploadFile($request->file('banner'), 'post_banner');
                $itemObj->banner_id = $imageArr['mediaObj']->id;
            }
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
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $model = $this->modelObj;
        $itemObj = $model::findOrFail($id);

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

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'post');
                $itemObj->media_id = $imageArr['mediaObj']->id;
            }
            if ($request->hasFile('banner')) {
                $imageArr = Custom::uploadFile($request->file('banner'), 'post_banner');
                $itemObj->banner_id = $imageArr['mediaObj']->id;
            }
            $itemObj->save();

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

    public function copy($url_key, $id, Request $request) {
        $obj = $this->modelObj::findOrFail($id);
        $new_obj = $obj->duplicate();

        if (!in_array($url_key, ['testimonials'])) {
            $new_obj->title = $new_obj->title . ' Copy';
        }

        if ($url_key == 'settings') {
            $new_obj->name = $new_obj->name . '_copy';
        } elseif ($url_key == 'testimonials') {
            $new_obj->company_name = $new_obj->company_name . ' Copy';
        }

        $new_obj->save();

        flash($this->singular_display_name . ' copied successfully')->success();
        return redirect($this->urls['list']);
    }

}

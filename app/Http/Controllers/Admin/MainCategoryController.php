<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\TopLevelCategory;
use App\Models\MainCategoryTopLevelCategory;

class MainCategoryController extends Controller {

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
            'top_level_categories' => TopLevelCategory::orderBy('title', 'ASC')->pluck('title', 'id'),
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
        ];

        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'search' => [
                'main_category_top_level_categories.top_level_category_id' => [
                    'title' => 'Top Level Category',
                    'options' => $this->common_data['top_level_categories'],
                ],
            ],
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        $data['main_category_top_level_categories'] = [];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    //'abbr' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $requestArr['post_type'] = strtolower($this->singular_display_name);
            $itemObj = $this->modelObj->create($requestArr);
            $itemObj->save();

            /*if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
            }*/

            $this->add_main_category_top_level_category_items($itemObj->id, $request->get('top_level_categories'));

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        $data['main_category_top_level_categories'] = MainCategoryTopLevelCategory::where('main_category_id', $id)->pluck('top_level_category_id');
        //dd($data);
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    //'abbr' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            /*if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
            }*/

            $this->add_main_category_top_level_category_items($itemObj->id, $request->get('top_level_categories'));
            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    private function add_main_category_top_level_category_items($main_category_id, $top_level_categories = []) {
        $del_query = MainCategoryTopLevelCategory::where('main_category_id', $main_category_id);
        if (!is_null($top_level_categories) && is_array($top_level_categories)) {
            $del_query->whereNotIn('top_level_category_id', $top_level_categories);
        }
        $del_query->delete();

        if (!is_null($top_level_categories) && is_array($top_level_categories)) {
            foreach ($top_level_categories as $top_level_category_id) {
                MainCategoryTopLevelCategory::firstOrCreate([
                    'top_level_category_id' => $top_level_category_id,
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

    public function reorder($top_level_category_id, Request $request) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        if (!is_null($top_level_category_id)) {
            $data['item_list'] = MainCategoryTopLevelCategory::where('top_level_category_id', $top_level_category_id)->orderBy('sort_order', 'ASC')->get();
        }
        $data['top_level_category_id'] = $top_level_category_id;
        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        //dd($request->all());
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                MainCategoryTopLevelCategory::where('id', $item)->update(['sort_order' => $counter++]);
            }
        }
    }

    public function getOptions(Request $request) {
        // Get All Main Categories
        $data['options'] = $this->modelObj->leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                ->where('main_category_top_level_categories.top_level_category_id', $request->get('top_level_category_id'))
                ->select('main_categories.title', 'main_categories.id')
                ->get();

        return view($this->view_base . '._get_options', $data);
    }

    public function getPplPrice(Request $request) {
        $main_category_item = $this->modelObj->find($request->get('main_category_id'));
        if (!is_null($main_category_item)) {
            return ['main_category_item_ppl_price' => $main_category_item->ppl_price];
        }
        return ['main_category_item_ppl_price' => ''];
    }

}

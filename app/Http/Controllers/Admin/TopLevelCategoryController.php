<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Trade;
use App\Models\TopLevelCategoryTrade;

class TopLevelCategoryController extends Controller {

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
            'trades' => Trade::pluck('title', 'id'),
            'tlc_max_id' => $this->modelObj->max('tlc_id') + 1
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
                'top_level_category_trades.trade_id' => [
                    'title' => 'Trade',
                    'options' => $this->common_data['trades'],
                ],
            ],
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        $data['top_level_category_trades'] = [];
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
            $requestArr['post_type'] = strtolower($this->singular_display_name);
            $itemObj = $this->modelObj->create($requestArr);

            $this->add_top_level_category_trade_items($itemObj->id, $request->get('trades'));

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        $data['top_level_category_trades'] = TopLevelCategoryTrade::where('top_level_category_id', $id)->pluck('trade_id');
        //dd($data);
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'tlc_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            $this->add_top_level_category_trade_items($itemObj->id, $request->get('trades'));
            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
        }
    }

    private function add_top_level_category_trade_items($top_level_category_id, $trades = []) {
        $del_query = TopLevelCategoryTrade::where('top_level_category_id', $top_level_category_id);
        if (!is_null($trades) && is_array($trades)) {
            $del_query->whereNotIn('trade_id', $trades);
        }
        $del_query->delete();

        if (!is_null($trades) && is_array($trades)) {
            foreach ($trades as $trade_id) {
                TopLevelCategoryTrade::firstOrCreate([
                    'top_level_category_id' => $top_level_category_id,
                    'trade_id' => $trade_id,
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

    public function reorder($trade_id, Request $request) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        if ($trade_id > 0) {
            $data['item_list'] = TopLevelCategoryTrade::where('trade_id', $trade_id)->orderBy('sort_order', 'ASC')->get();
        }
        $data['trade_id'] = $trade_id;
        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                TopLevelCategoryTrade::where('id', $item)->update(['sort_order' => $counter++]);
            }
        }
    }

    public function getOptions(Request $request) {
        // Get All Top Level Categories
        $data['options'] = $this->modelObj->leftJoin('top_level_category_trades', 'top_level_categories.id', 'top_level_category_trades.top_level_category_id')
                ->where('top_level_category_trades.trade_id', $request->get('trade_id'))
                ->select('top_level_categories.title', 'top_level_categories.id')
                ->get();

        return view($this->view_base . '._get_options', $data);
    }

}

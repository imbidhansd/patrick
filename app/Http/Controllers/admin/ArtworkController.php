<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;

class ArtworkController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
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

        $artworkTypeArr = [
            'social_media' => 'Social Media',
            'print_ready' => 'Print Ready',
        ];

        $social_type = [
            'Facebook' => 'Facebook',
            'Instagram' => 'Instagram',
            //'Google Plus' => 'Google Plus',
        ];

        $bannerOptions = [
            'Founding Member' => 'Founding Member',
            'Official Member' => 'Official Member',
            'Recommended Company' => 'Recommended Company',
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'artworkTypeArr' => $artworkTypeArr,
            'social_type' => $social_type,
            'banner_for_options' => $bannerOptions,
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $artwork_type = '';

        $search_column = [
            'artworks.artwork_for' => [
                'title' => 'Artwork For',
                'options' => $this->common_data['banner_for_options'],
                'id' => ''
            ],
        ];

        if ($request->has('artwork_type') && $request->get('artwork_type') != '') {
            $list_params['artwork_type'] = $request->get('artwork_type');

            $artwork_type = '[' . ucwords(str_replace('_', ' ', $list_params['artwork_type'])) . ']';

            if ($list_params['artwork_type'] == 'social_media') {
                $search_column = [
                    'artworks.artwork_for' => [
                        'title' => 'Artwork For',
                        'options' => $this->common_data['banner_for_options'],
                        'id' => ''
                    ],
                    'artworks.social_type' => [
                        'title' => 'Social Type',
                        'options' => $this->common_data['social_type'],
                        'id' => ''
                    ],
                ];
            }
        }
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        /* 'artworks.artwork_type' => [
          'title' => 'Artwork Type',
          'options' => $this->common_data['artworkTypeArr'],
          ], */

        $data = [
            'admin_page_title' => $admin_page_title . ' ' . $artwork_type,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => $search_column
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create(Request $request) {
        $artwork_type_title = $artwork_type = '';
        if ($request->has('artwork_type') && $request->get('artwork_type') != '') {
            $artwork_type_title = ' [' . ucwords(str_replace('_', ' ', $request->get('artwork_type'))) . ']';
            $artwork_type = $request->get('artwork_type');
        }

        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name . ' ' . $artwork_type_title,
            'artwork_type' => $artwork_type,
        ];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'artwork_type' => 'required',
                    'artwork_for' => 'required',
                    'title' => 'required',
                    //'image_type' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj = $this->modelObj->create($requestArr);

            if ($request->hasFile('jpg_media')) {
                $imageArr = Custom::uploadFile($request->file('jpg_media'), $this->post_type);
                $itemObj->jpg_media_id = $imageArr['mediaObj']->id;
            }

            if ($request->hasFile('png_media')) {
                $imageArr = Custom::uploadFile($request->file('png_media'), $this->post_type);
                $itemObj->png_media_id = $imageArr['mediaObj']->id;
            }

            if ($request->hasFile('pdf_media')) {
                $imageArr = Custom::uploadFile($request->file('pdf_media'), $this->post_type);
                $itemObj->pdf_media_id = $imageArr['mediaObj']->id;
            }
            $itemObj->save();

            flash($this->module_messages['add'])->success();
            if (isset($requestArr['artwork_type']) && $requestArr['artwork_type'] != '') {
                return redirect($this->urls['list'] . '?artwork_type=' . $requestArr['artwork_type']);
            } else {
                return redirect($this->urls['list']);
            }
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);

        $artwork_type_title = $artwork_type = '';
        if ($formObj->artwork_type != '') {
            $artwork_type_title = ' [' . ucwords(str_replace('_', ' ', $formObj->artwork_type)) . ']';
            $artwork_type = $formObj->artwork_type;
        }

        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name . ' ' . $artwork_type_title;
        $data['formObj'] = $formObj;
        $data['artwork_type'] = $formObj->artwork_type;

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'artwork_type' => 'required',
                    'artwork_for' => 'required',
                    'title' => 'required',
                    //'image_type' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            if ($request->hasFile('jpg_media')) {
                $imageArr = Custom::uploadFile($request->file('jpg_media'), $this->post_type);
                $itemObj->jpg_media_id = $imageArr['mediaObj']->id;
            }

            if ($request->hasFile('png_media')) {
                $imageArr = Custom::uploadFile($request->file('png_media'), $this->post_type);
                $itemObj->png_media_id = $imageArr['mediaObj']->id;
            }

            if ($request->hasFile('pdf_media')) {
                $imageArr = Custom::uploadFile($request->file('pdf_media'), $this->post_type);
                $itemObj->pdf_media_id = $imageArr['mediaObj']->id;
            }
            $itemObj->save();

            flash($this->module_messages['update'])->success();
            if (isset($requestArr['artwork_type']) && $requestArr['artwork_type'] != '') {
                return redirect($this->urls['list'] . '?artwork_type=' . $requestArr['artwork_type']);
            } else {
                return redirect($this->urls['list']);
            }
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

    public function reorder($artwork_type = null, Request $request) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        if (!is_null($artwork_type)) {
            $data['item_list'] = $this->modelObj->where('artwork_type', $artwork_type)->orderBy('sort_order', 'ASC')->get();
        }
        $data['artworkTypeId'] = $artwork_type;
        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        //dd($request->all());
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                $this->modelObj->where('id', $item)->update(['sort_order' => $counter++]);
            }
        }
    }

}

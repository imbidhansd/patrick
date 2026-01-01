<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
// Models [start]
use App\Models\Custom;

class MediaController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = 'Media';

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
        $this->singular_display_name = 'Media';
        $this->module_plural_name = 'Media';

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
        $data = ['admin_page_title' => 'Upload ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $other_images = $request->file('file');

            if (count($other_images) > 0) {
                foreach ($other_images as $file) {
                    Custom::uploadFile($file, 'media');
                }
            }
            flash('File(s) uploaded successfully')->success();
            return redirect($this->urls['list']);
        }
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->warning();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function deleteMedia(Request $request) {
        $this->modelObj->find($request->get('media_id'))->delete();
        if ($request->has('setting_id') && $request->get('setting_id') > 0) {
            $setting_obj = \App\Models\Setting::find($request->get('setting_id'));
            if (!is_null($setting_obj)) {
                $setting_obj->value = '';
                $setting_obj->save();
            }
        }
    }

    public function editorUpload(Request $request) {
        if ($request->has('upload')) {
            $file = $request->file('upload');
            $imageArr = Custom::uploadFile($file, 'media');

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('uploads/media/' . $imageArr['filename']);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
            //return ['default' => url('/') . '/uploads/media/' . $imageArr['filename']];
        }
    }

    public function resizeImages() {
        $all_media = $this->modelObj->all();
        $imgExtensionArry = ['jpg', 'jpeg', 'png', 'bmp', 'tiff'];
        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'media';

        if (count($all_media) > 0) {
            foreach ($all_media as $media_item) {
                if ($media_item->file_extension != '' && in_array($media_item->file_extension, $imgExtensionArry)) {
                    $fullpath = $uploadPath . DIRECTORY_SEPARATOR . $media_item->file_name;

                    if (env('FIT_THUMBS') != '') {
                        Custom::createThumbnails($fullpath, 'fit_thumbs', $media_item->file_name, env('FIT_THUMBS'));
                    }
                    if (env('HEIGHT_THUMBS') != '') {
                        Custom::createThumbnails($fullpath, 'height_thumbs', $media_item->file_name, env('HEIGHT_THUMBS'));
                    }
                    if (env('WIDTH_THUMBS') != '') {
                        Custom::createThumbnails($fullpath, 'width_thumbs', $media_item->file_name, env('WIDTH_THUMBS'));
                    }
                }
            }
        }
    }

}

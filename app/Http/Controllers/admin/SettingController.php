<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Setting;

class SettingController extends Controller {

    public function __construct() {
        $this->url_key = 'settings';

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular('Setting');
        $this->module_plural_name = Str::plural('Setting');

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
        ];

        View::share($this->common_data);
        // View
        $this->view_base = 'admin.' . $this->url_key;
    }

    public function settings(Request $request) {
        $data['settings'] = Setting::where('status', 'active')->orderBy('sort_order', 'asc')->get();
        $data['admin_page_title'] = 'Website ' . $this->module_plural_name;

        return view($this->view_base . '.settings', $data);
    }

    public function updateSettings(Request $request) {
        $requestArr = $request->all();
        if (isset($requestArr['settings']) && count($requestArr['settings']) > 0) {
            foreach ($requestArr['settings'] as $key => $val) {
                $settingObj = Setting::find($key);
                $settingObj->update(['value' => $val]);
            }
        }

        $images = $request->file('file');
        if ($request->has('file') && count($images) > 0) {
            foreach ($images as $key => $file) {
                $obj = Custom::uploadFile($file, 'media');

                $arr = [
                    'id' => $obj['mediaObj']->id,
                    'filename' => $obj['filename'],
                ];
                $settingObj = Setting::find($key);
                $settingObj->update(['value' => json_encode($arr)]);
            }
        }

        flash('Settings has been updated successfuly')->success();
        return back();
    }    

    public function copy($id, Request $request) {

        $urls = Custom::getModuleUrls($this->url_key);

        $setting_obj = Setting::findOrFail($id);
        $new_obj = $setting_obj->replicate();

        $new_obj->name = $new_obj->name . '_copy';
        $new_obj->title = $new_obj->title . ' Copy';
        $new_obj->save();

        session()->flash('success_message', 'Setting copied successfully');
        return redirect(route($urls['edit'], [$urls['url_key_singular'] => $new_obj->id]));
    }    
}

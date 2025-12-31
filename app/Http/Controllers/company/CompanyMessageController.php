<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Str;
use Auth;
use Session;
use App\Models\Company;
use App\Models\Custom;

class CompanyMessageController extends Controller {

    public function __construct() {
        $segment = \Request::segment(1);

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
        ];

        View::share($this->common_data);

        $this->view_base = 'company.messages.';
    }

    public function index(Request $request) {
        $company_id = Auth::guard('company_user')->user()->company_id;

        $data = [
            'company_detail' => Company::find($company_id),
            'company_messages_list' => $this->modelObj->where('company_id', $company_id)->active()->order()->paginate(env('APP_RECORDS_PER_PAGE'))
        ];

        return view($this->view_base . 'index', $data);
    }

    public function destroy(Request $request, $id) {
        $modelObj = $this->modelObj->findOrFail($id);
        $modelObjTemp = $modelObj;

        try {
            $modelObj->delete();
            flash($this->module_messages['delete'])->success();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function update_status(Request $request) {
        // Update Status
        if (in_array($request->get('action'), ['checked', 'not_checked'])) {
            if (!Session::has('company_mask')) {
                $updateArr = [
                    'checked_at' => \Carbon\Carbon::now()
                ];

                if ($request->get('action') == 'checked') {
                    $updateArr['checked'] = 'yes';
                } else if ($request->get('action') == 'not_checked') {
                    $updateArr['checked'] = 'no';
                }

                $this->modelObj->whereIn('id', $request->get('ids'))->update($updateArr);
                flash('Row(s) status has been changed successfully')->success();
            }
        } else if (in_array($request->get('action'), ['delete'])) {
            $this->modelObj->whereIn('id', $request->get('ids'))->delete();
            flash('Row(s) has been deleted successfully')->success();
        }

        if (!$request->has('ajax_form')) {
            return back();
        }
    }

}

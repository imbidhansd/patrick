<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use \Carbon\Carbon;
use Str;
use Arr;
use DB;
use Auth;
// Models [start]
use App\Models\Custom;
use App\Models\User;
use App\Models\CompanyChargeSetting;

class CommonController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);

        if (!in_array($segment, ['update-status', '2fa-qr-code', 'company-charge'])) {
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

            View::share([
                'module_singular_name' => $this->singular_display_name,
                'module_plural_name' => $this->module_plural_name,
                'url_key' => $url_key,
                'module_urls' => $this->urls,
            ]);

            // View
            $this->view_base = 'admin.' . $url_key;
        } // $segment != 'update-status'
    }

    public function reorder(Request $request, $table_name = null) {
        if (is_null($table_name)) {
            abort(404);
        }

        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        $data['item_list'] = $this->modelObj->orderBy('sort_order', 'ASC')->get();
        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        if ($request->has('items') && count($request->get('items')) > 0) {
            $counter = 1;
            foreach ($request->get('items') as $item) {
                $item = $this->modelObj->find($item);
                $item->update(['sort_order' => $counter++]);
            }
        }
    }

    // Update Status for Common use for all modules.

    public function updateStatus(Request $request) {
        // Update Status
        if (in_array($request->get('action'), ['active', 'inactive', 'Publish', 'Not Publish', 'new', 'pending', 'resolved'])) {
            DB::table($request->get('url_key'))->whereIn('id', $request->get('ids'))->update(['status' => $request->get('action')]);
            flash('Row(s) status has been changed successfully')->success();
        } elseif (in_array($request->get('action'), ['delete'])) {
            DB::table($request->get('url_key'))->whereIn('id', $request->get('ids'))->delete();
            flash('Row(s) has been deleted successfully')->success();
        }
        return back();
    }

    // Show QR Code

    public function showQrCode($qr_code_key, Request $request) {

        // redirect if already logged in
        if (Auth::check()) {
            return redirect($this->paths['dashboard']);
        }

        $userObj = User::where('qr_code_key', $qr_code_key)->first();
        if (is_null($userObj)) {
            abort(404);
        }

        $google2fa = app('pragmarx.google2fa');

        $QR_Image = $google2fa->getQRCodeInline(
                env('APP_NAME'), $userObj->email, $userObj->google2fa_secret
        );

        $data = [
            'admin_page_title' => 'Scan QR Code',
            'qr_code' => $QR_Image,
            'secret' => $userObj->google2fa_secret,
        ];

        return view('admin.users.show_qr_code', $data);
    }

    public function postShowQrCode($qr_code_key, Request $request) {
        // redirect if already logged in
        if (Auth::check()) {
            return redirect($this->paths['dashboard']);
        }

        $userObj = User::where('qr_code_key', $qr_code_key)->first();
        if (is_null($userObj)) {
            abort(404);
        }

        $userObj->qr_code_key = '';
        $userObj->save();

        return redirect('admin/login');
    }

    public function get_email_variables(Request $request) {
        $common_variables = config('email_keywords.common');

        $data = [
            'mail_variables' => $common_variables
        ];
        if ($request->has('email_type') && $request->get('email_type') != '' && $request->has('email_title') && $request->get('email_title') != '') {
            $mail_variables = config('email_keywords.companies.' . $request->get('email_type') . '.' . $request->get('email_title'));

            if (isset($mail_variables) && count($mail_variables) > 0) {
                $data = [
                    'mail_variables' => array_merge($mail_variables, $common_variables)
                ];
            }
        }

        return view('admin.emails._email_variables', $data);
    }

}

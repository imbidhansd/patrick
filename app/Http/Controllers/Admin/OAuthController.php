<?php

namespace App\Http\Controllers\Admin;
use App\Models\Custom;
use App\Models\Aweber;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Str;
use View;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function __construct() {
        $segment = \Request::segment(2);
        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);
        $this->common_data = [];
        View::share($this->common_data);
        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $auth = Aweber::Authorize($request->input('code'));
        $refreshToken = '';
        $accessToken = '';
        $accountId = '';
        if( $auth != null ){
          $refreshToken = $auth->refresh_token;
          $accessToken = $auth->access_token;
          $accountId = Aweber::GetAccountId($accessToken);
        }

        $data = [
            'admin_page_title' => 'Aweber Configuration',
            'refresh_token' => $refreshToken,
            'access_token' => $accessToken,
            'account_id' => $accountId
        ];

        return view($this->view_base . '.index', $data);
    }
}

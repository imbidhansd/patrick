<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\DefaultEmailHeaderFooter;

class NewEmailController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
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

        $this->singular_display_name = 'Email';
        $this->module_plural_name = 'Emails';

        $email_type = [
            'Registration Email',
            'Upgrade Email',
            'Application Email',
            'Leads',
            'Subscription',
            'FAQ Question',
            'Feedback',
            'Complaint',
            'Member Resources',
            'Package',
            'Company Gallery',
            'Non Members'
        ];

        $email_for = [
            'admin' => 'Admin',
            'company' => 'Company',
            'consumer' => 'Consumer'
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'email_for' => $email_for,
            'email_type' => array_combine($email_type, $email_type)
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $email_for = '';
        if ($request->has('email_for') && $request->get('email_for') != '') {
            $list_params['email_for'] = $request->get('email_for');

            $email_for = '[' . ucfirst($list_params['email_for']) . ']';
        }

        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name . ' ' . $email_for,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'new_emails.email_type' => [
                    'title' => 'Email Type',
                    'options' => $this->common_data['email_type'],
                    'id' => 'email_type'
                ]
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
        ];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
                    'email_for' => 'required',
                    'email_type' => 'required',
                    'title' => 'required',
                    'from_email_address' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $this->modelObj->create($requestArr);

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list'] . '?email_for=' . $requestArr['email_for']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        $data['mail_variables'] = config('new_email_keywords.' . $formObj->title);
        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
            'mail_variables' => config('new_email_keywords.' . $formObj->title)
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'email_for' => 'required',
                    //'email_type' => 'required',
                    'title' => 'required',
                    'from_email_address' => 'required',
                    'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list'] . '?email_for=' . $requestArr['email_for']);
        }
    }

    public function destroy($id) {
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

}

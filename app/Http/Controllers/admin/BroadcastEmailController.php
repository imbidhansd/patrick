<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\Trade;
use App\Models\MainCategory;
use App\Models\ServiceCategory;
use App\Models\TopLevelCategory;
use App\Models\DefaultEmailHeaderFooter;
use App\Models\MembershipLevel;

class BroadcastEmailController extends Controller {

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

        // Post type
        $this->post_type = $url_key;

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = Str::plural($module_display_name);

        $subscription_type = [
            'regarding_your_request' => 'Regarding Your Request',
            'special_offers' => 'Special Promotions/Offers',
            'scams_updates' => 'Scams & Ripoffs Updates',
            'general_updates' => 'General Updates',
        ];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'trades' => Trade::active()->order()->pluck('title', 'id'),
            'subscription_type' => $subscription_type,
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage ' . $this->module_plural_name;

        if ($request->has('email_type') && $request->get('email_type') != '') {
            $list_params['email_type'] = $request->get('email_type');

            $admin_page_title .= ' [' . ucwords(str_replace('_', ' ', $request->get('email_type'))) . ']';
        }
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $top_level_categories = $main_categories = $service_categories = null;

        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['broadcast_emails.trade_id']) && $requestArr['broadcast_emails.trade_id'] != '') {
                $top_level_categories = TopLevelCategory::active()
                        ->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', 'top_level_categories.id')
                        ->where('top_level_category_trades.trade_id', $requestArr['broadcast_emails.trade_id'])
                        ->orderBy('top_level_categories.title', 'ASC')
                        ->pluck('top_level_categories.title', 'top_level_categories.id');
            }

            if (isset($requestArr['broadcast_emails.top_level_category_id']) && $requestArr['broadcast_emails.top_level_category_id'] != '') {
                $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                        ->where('main_category_top_level_categories.top_level_category_id', $requestArr['broadcast_emails.top_level_category_id'])
                        ->pluck('main_categories.title', 'main_categories.id');
            }

            if (isset($requestArr['broadcast_emails.main_category_id']) && $requestArr['broadcast_emails.main_category_id'] != '') {
                $service_categories = ServiceCategory::where([
                            ['main_category_id', $requestArr['broadcast_emails.main_category_id']],
                            ['top_level_category_id', $requestArr['broadcast_emails.top_level_category_id']]
                        ])
                        ->active()
                        ->order()
                        ->pluck('title', 'id');
            }
        }

        $data = [
            'admin_page_title' => $admin_page_title,
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'broadcast_emails.trade_id' => [
                    'title' => 'Trade',
                    'options' => $this->common_data['trades'],
                    'id' => 'trade_id'
                ],
                'broadcast_emails.top_level_category_id' => [
                    'title' => 'Top Level Category',
                    'options' => $top_level_categories,
                    'id' => 'top_level_category_id'
                ],
                'broadcast_emails.main_category_id' => [
                    'title' => 'Main Category',
                    'options' => $main_categories,
                    'id' => 'main_category_id'
                ],
                'broadcast_emails.service_category_id' => [
                    'title' => 'Service Category',
                    'options' => $service_categories,
                    'id' => 'service_category_id'
                ],
                'broadcast_emails.subscription_type' => [
                    'title' => 'Subscription type',
                    'options' => $this->common_data['subscription_type'],
                ]
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create(Request $request) {
        $email_type_title = '';
        if ($request->has('email_type') && $request->get('email_type') != '') {
            $email_type_title = ' [' . ucwords(str_replace('_', ' ', $request->get('email_type'))) . ']';

            if ($request->get('email_type') == 'registered_members') {
                $email_for = MembershipLevel::whereIn('id', ['1', '2', '3'])->active()->order()->pluck('title', 'id');
            } else if ($request->get('email_type') == 'official_members') {
                $email_for = MembershipLevel::whereIn('id', ['4', '5', '6', '7'])->active()->order()->pluck('title', 'id');
            }
        }

        $data = [
            'admin_page_title' => 'Create ' . $this->singular_display_name . $email_type_title,
            'email_type' => (($request->has('email_type') && $request->get('email_type') != '') ? $request->get('email_type') : null),
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
            'email_for' => ((isset($email_for) && !is_null($email_for)) ? $email_for : null),
        ];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        //dd($request->all());
        $rules = [
            'from_email_address' => 'required',
            'subject' => 'required',
            'content' => 'required',
            'when_send' => 'required'
        ];

        if ($request->input('when_send') == 'later') {
            $rules['send_datetime'] = 'required|regex:/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}$/';
        }


        $validator = Validator::make($request->all(), $rules,
        [
            'send_datetime.regex' => 'The Enter Send DateTime field must be in the format MM/DD/YYYY HH:mm.',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            if ($requestArr['when_send'] == 'later') {
                $requestArr['send_datetime'] = \Carbon\Carbon::createFromFormat(env('DATETIME_FORMAT', 'm/d/Y H:i:s'), $requestArr['send_datetime'] . ':00')->format(env('DB_DATETIME_FORMAT', 'Y-m-d H:i:s'));
            }

            //dd($requestArr);
            $itemObj = $this->modelObj->create($requestArr);

            if ($requestArr['draft_message'] == 'no') {
                if ($requestArr['when_send'] == 'now') {
                    if (!is_null($itemObj->email_type)) {
                        if ($itemObj->email_type == 'non_members') {
                            $return1 = Custom::send_non_member_broadcast_email($itemObj);
                        } else if ($itemObj->email_type == 'registered_members') {
                            $return2 = Custom::send_registered_member_broadcast_email($itemObj);
                        } else if ($itemObj->email_type == 'official_members') {
                            $return3 = Custom::send_official_member_broadcast_email($itemObj);
                        }
                    } else {
                        $return = Custom::send_lead_broadcast_email($itemObj);
                    }

                    $itemObj->mail_sent = 'yes';
                    $itemObj->save();
                }
            }


            flash($this->module_messages['add'])->success();
            if (isset($requestArr['email_type']) && $requestArr['email_type'] != '') {
                return redirect($this->urls['list'] . '?email_type=' . $requestArr['email_type']);
            } else {
                return redirect($this->urls['list']);
            }
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $email_type_title = '';
        if (!is_null($formObj->email_type)) {
            $email_type_title = ' [' . ucwords(str_replace('_', ' ', $formObj->email_type)) . ']';

            if ($formObj->email_type == 'registered_members') {
                $email_for = MembershipLevel::whereIn('id', ['1', '2', '3'])->active()->order()->pluck('title', 'id');
            } else if ($formObj->email_type == 'official_members') {
                $email_for = MembershipLevel::whereIn('id', ['4', '5', '6', '7'])->active()->order()->pluck('title', 'id');
            }
        }

        if (!is_null($formObj->send_datetime)) {
            $formObj->send_datetime = \Carbon\Carbon::createFromFormat(env('DB_DATETIME_FORMAT', 'Y-m-d H:i:s'), $formObj->send_datetime)->format(env('DATETIME_BROADCAST_FORMAT', 'm/d/Y H:i'));
        }

        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name . $email_type_title,
            'formObj' => $formObj,
            'email_type' => $formObj->email_type,
            'header_emails' => DefaultEmailHeaderFooter::emailtype('header')->pluck('title', 'id'),
            'footer_emails' => DefaultEmailHeaderFooter::emailtype('footer')->pluck('title', 'id'),
            'email_for' => ((isset($email_for) && !is_null($email_for)) ? $email_for : null),
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'from_email_address' => 'required',
                    'subject' => 'required',
                    'content' => 'required',
                    'when_send' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            if ($requestArr['when_send'] == 'later') {
                $requestArr['send_datetime'] = \Carbon\Carbon::createFromFormat(env('DATETIME_FORMAT', 'm/d/Y H:i:s'), $requestArr['send_datetime'] . ':00')->format(env('DB_DATETIME_FORMAT', 'Y-m-d H:i:s'));
            }
            $itemObj->update($requestArr);

            if ($requestArr['draft_message'] == 'no') {
                if ($requestArr['when_send'] == 'now') {
                    if (!is_null($itemObj->email_type)) {
                        if ($itemObj->email_type == 'non_members') {
                            $return1 = Custom::send_non_member_broadcast_email($itemObj);
                        } else if ($itemObj->email_type == 'registered_members') {
                            $return2 = Custom::send_registered_member_broadcast_email($itemObj);
                        } else if ($itemObj->email_type == 'official_members') {
                            $return3 = Custom::send_official_member_broadcast_email($itemObj);
                        }
                    } else {
                        $return = Custom::send_lead_broadcast_email($itemObj);
                    }

                    $itemObj->mail_sent = 'yes';
                    $itemObj->save();
                }
            }


            flash($this->module_messages['add'])->success();
            if (isset($itemObj->email_type) && $itemObj->email_type != '') {
                return redirect($this->urls['list'] . '?email_type=' . $itemObj->email_type);
            } else {
                return redirect($this->urls['list']);
            }
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

    /* Ajax functions start */

    public function get_top_level_categories(Request $request) {
        $top_level_categories = null;
        // Find Top Level Categories from trade_id

        if ($request->has('trade_id') && $request->get('trade_id') > 0) {
            $top_level_categories = TopLevelCategory::active()
                    ->leftJoin('top_level_category_trades', 'top_level_category_trades.top_level_category_id', '=', 'top_level_categories.id')
                    ->where('top_level_category_trades.trade_id', $request->get('trade_id'))
                    ->orderBy('top_level_categories.title', 'ASC')
                    ->pluck('top_level_categories.title', 'top_level_categories.id');
        }

        $data = [
            'top_level_categories' => $top_level_categories,
        ];
        return view($this->view_base . '._top_level_categories', $data);
    }

    public function get_main_categories(Request $request) {
        $main_categories = null;
        if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '') {
            $main_categories = MainCategory::leftJoin('main_category_top_level_categories', 'main_category_top_level_categories.main_category_id', '=', 'main_categories.id')
                    ->where('main_category_top_level_categories.top_level_category_id', $request->get('top_level_category_id'))
                    ->active()
                    ->orderBy('main_categories.title', 'ASC')
                    ->pluck('main_categories.title', 'main_categories.id');
        }

        $data = [
            'main_categories' => $main_categories,
        ];
        return view($this->view_base . '._main_categories', $data);
    }

    public function get_service_categories(Request $request) {
        $service_categories = null;

        if ($request->has('top_level_category_id') && $request->get('top_level_category_id') != '' && $request->has('main_category_id') && $request->get('main_category_id') != '') {
            $service_categories = ServiceCategory::where([
                        ['main_category_id', $request->get('main_category_id')],
                        ['top_level_category_id', $request->get('top_level_category_id')]
                    ])
                    ->active()
                    ->order()
                    ->pluck('title', 'id');
        }

        $data = [
            'service_categories' => $service_categories
        ];
        return view($this->view_base . '._service_categories', $data);
    }

    /* Ajax functions end */
}

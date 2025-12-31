<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;
use App\Models\MembershipLevel;
use App\Models\MembershipStatus;

class FaqController extends Controller {

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
            'membership_levels' => MembershipLevel::active()->order()->pluck('title', 'id'),
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {
        $list_params = Custom::getListParams($request);
        $admin_page_title = 'Manage ' . $this->module_plural_name;
        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }

        $membership_status = [];
        if ($request->has('search')) {
            $requestArr = $request->get('search');

            if (isset($requestArr['faqs.membership_level_id']) && $requestArr['faqs.membership_level_id'] > 0) {
                $membership_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                        ->where('membership_level_statuses.membership_level_id', $requestArr['faqs.membership_level_id'])
                        ->active()
                        ->orderBy('membership_statuses.title', 'ASC')
                        ->pluck('membership_statuses.title', 'membership_statuses.id');
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
                'faqs.membership_level_id' => [
                    'title' => 'Membership Level',
                    'options' => $this->common_data['membership_levels'],
                    'id' => 'membership_level_id'
                ],
                'faqs.membership_status_id' => [
                    'title' => 'Membership Status',
                    'options' => $membership_status,
                    'id' => 'membership_status_id'
                ]
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create() {
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect($this->urls['add'])
            ->withErrors($validator)
            ->withInput();
    } else {
        $requestData = $request->all();
        
        // Check if membership_status_id is an array (indicating multiple selections)
        if (is_array($requestData['membership_status_id']) && is_array($requestData['membership_level_id']) ) {
            $membership_level_by_status = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
            ->whereIn('membership_level_statuses.membership_level_id', $requestData['membership_level_id'])
            ->active()
            ->orderBy('membership_statuses.title', 'ASC')
            ->get(['membership_level_statuses.membership_level_id', 'membership_statuses.id']);

            foreach ($requestData['membership_level_id'] as $membershipLevelId) {
                foreach ($requestData['membership_status_id'] as $membershipStatusId) {
                    $matchingRecord = $membership_level_by_status->first(function ($record) use ($membershipStatusId, $membershipLevelId) {
                        return $record->id == $membershipStatusId && $record->membership_level_id == $membershipLevelId;
                    });
                    if( $matchingRecord) {
                        $itemData = $requestData;
                        $itemData['membership_status_id'] = $membershipStatusId;
                        $itemData['membership_level_id'] = $membershipLevelId;
                        $itemObj = $this->modelObj->create($itemData);
                    }
                }
            }
        } else {
            // If membership_status_id is not an array (single selection)
            $itemObj = $this->modelObj->create($requestData);
        }

        flash($this->module_messages['add'])->success();
        return redirect($this->urls['list']);
    }
}

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data = [
            'admin_page_title' => 'Edit ' . $this->singular_display_name,
            'formObj' => $formObj,
        ];

        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list']);
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

    public function reorder(Request $request) {
        $data['admin_page_title'] = 'Reorder ' . $this->module_plural_name;
        $data['item_list'] = $this->modelObj->order()->get();

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

    /* Ajax Call method */

    public function get_membership_status_from_level(Request $request) {
        $membershipLevelIds = $request->input('membership_level_id');
        $membershipLevelIds = array_map('trim', $membershipLevelIds);

        if (!is_array($membershipLevelIds)) {
            $membershipLevelIds = [$membershipLevelIds];            
        }
    
        if (!empty($membershipLevelIds)) {
            $data['membership_status'] = MembershipStatus::leftJoin('membership_level_statuses', 'membership_statuses.id', 'membership_level_statuses.membership_status_id')
                    ->whereIn('membership_level_statuses.membership_level_id', $membershipLevelIds)
                    ->active()
                    ->orderBy('membership_statuses.title', 'ASC')
                    ->pluck('membership_statuses.title', 'membership_statuses.id');           
            return view($this->view_base . '._membership_level_status_selection', $data);
        } else {
            return [
                'success' => 0,
                'message' => 'Select Level first.'
            ];
        }
    }

}

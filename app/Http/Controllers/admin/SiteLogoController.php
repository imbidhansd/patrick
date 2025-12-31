<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
// Models [start]
use App\Models\Custom;

class SiteLogoController extends Controller {
    const FOUNDING_MEMBER = 'Founding Member';
    const OFFICIAL_MEMBER = 'Official Member';
    const RECOMMENDED_COMPANY = 'Recommended Company';
    const CERTIFIED_PRO = 'Certified Pro';
    const DOMAIN_SLUG_TP = 'tp';
    const DOMAIN_SLUG_AAD = 'aad';

    public function __construct() {        
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }
      
        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));
        // Links
        $this->urls = Custom::getModuleUrls($url_key);      
       
        $this->post_type = $url_key;

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

        $mediaTypeArr = ['banner', 'logo'];

        $this->common_data = [
            'module_singular_name' => $this->singular_display_name,
            'module_plural_name' => $this->module_plural_name,
            'url_key' => $url_key,
            'module_urls' => $this->urls,
            'mediaTypeArr' => array_combine($mediaTypeArr, array_map('ucfirst', $mediaTypeArr)),
            'banner_for_options' => [self::FOUNDING_MEMBER,self::OFFICIAL_MEMBER,self::RECOMMENDED_COMPANY,self::CERTIFIED_PRO],
        ];

        View::share($this->common_data);

        // View
        $this->view_base = 'admin.' . $url_key;
    }

    public function index(Request $request) {

        if (!Custom::checkForPermission($this->common_data['url_key'])) {
            return redirect('admin/dashboard');
        }

        $list_params = Custom::getListParams($request);
        $domain_slug = $request->query('domain_slug') ?? self::DOMAIN_SLUG_TP;
        if($domain_slug !== null && trim($domain_slug) !== '')
        {
            $list_params["domain_slug"] = $domain_slug;
        }

        $rows = $this->modelObj->getAdminList($list_params);

        if (count($rows) <= 0 && $request->has('page') && $request->get('page') > 1) {
            $list_params['page'] = $rows->lastPage();
            return redirect($this->urls['list'] . http_build_query($list_params));
        }
        $url_key = $this->common_data['url_key'];
        
        $query_param = '?domain_slug=tp';
        if($domain_slug !== null && trim($domain_slug) !== '')
        {
           $query_param = '?domain_slug='.$domain_slug;
        }      
        $this->common_data['module_urls']['list'] = route($url_key . '.index').$query_param;
        $this->common_data['module_urls']['add'] = route($url_key . '.create').$query_param;
        $this->common_data['module_urls']['store'] = route($url_key . '.store').$query_param;   
        $this->common_data['module_urls']['reorder'] = url("admin/" . $url_key . "/re-order").$query_param;               
        View::share($this->common_data);
        $data = [
            'admin_page_title' => 'Manage ' . $this->module_plural_name . ' for ' . Custom::getFullDomain($domain_slug),
            'rows' => $rows,
            'list_params' => $list_params,
            'searchColumns' => $this->modelObj->searchColumns,
            'with_date' => 0,
            'action_arr' => Custom::getActionArr($this->common_data['url_key']),
            'search' => [
                'site_logos.media_type' => [
                    'title' => 'Media type',
                    'options' => $this->common_data['mediaTypeArr'],
                    'id' => ''
                ],
            ]
        ];

        return view($this->view_base . '.index', $data);
    }

    public function create(Request $request) {
        if (!Custom::checkForPermission($this->common_data['url_key'], 'create')) {
            return redirect('admin/dashboard');
        }
        $url_key = $this->common_data['url_key'];
        $domain_slug = $request->query('domain_slug')  ?? self::DOMAIN_SLUG_TP;
        $query_param = '';
        if($domain_slug !== null && trim($domain_slug) !== '')
        {
           $query_param = '?domain_slug='.$domain_slug;
        }

        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name. ' for '.Custom::getFullDomain($domain_slug)];
        $this->common_data['module_urls']['list'] = route($url_key . '.index').$query_param;
        $this->common_data['module_urls']['add'] = route($url_key . '.create').$query_param;
        $this->common_data['module_urls']['store'] = route($url_key . '.store').$query_param;
        $this->common_data['banner_domain_fullname'] = Custom::getFullDomain($domain_slug);
        View::share($this->common_data);
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        if (!Custom::checkForPermission($this->common_data['url_key'], 'create')) {
            return redirect('admin/dashboard');
        }
        $data = $request->all();
        $domain_slug = $request->query('domain_slug');
        $query_param = '';
        if($domain_slug !== null && trim($domain_slug) !== '')
        {
           $query_param = '?domain_slug='.$domain_slug;
        }

        $data['domain_slug'] = $domain_slug;
        $validator = Validator::make($data, [
                    'title' => 'required',
                    'size' => 'required',
                    'banner_for' => 'required',
                    'banner_url' => 'required',
                    'domain_slug' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $itemObj = $this->modelObj->create($data);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
            }

            $itemObj->save();

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list'].$query_param);
        }
    }

    public function edit(Request $request, $id) {
        if (!Custom::checkForPermission($this->common_data['url_key'], 'edit')) {
            return redirect('admin/dashboard');
        }

        $formObj = $this->modelObj->findOrFail($id);
        
        $this->common_data['banner_domain_fullname'] = Custom::getFullDomain($formObj->domain_slug);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name . ' for ' . Custom::getFullDomain($formObj->domain_slug);
        $data['formObj'] = $formObj;
        View::share($this->common_data);
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        if (!Custom::checkForPermission($this->common_data['url_key'], 'edit')) {
            return redirect('admin/dashboard');
        }

        $itemObj = $this->modelObj->findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'size' => 'required',
                    'banner_for' => 'required',
                    'banner_url' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $row->id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), $this->post_type);
                $itemObj->media_id = $imageArr['mediaObj']->id;
                $itemObj->save();
            }
            $query_param = '?domain_slug='.$itemObj->domain_slug;
            flash($this->module_messages['update'])->success();
            return redirect($this->urls['list'].$query_param);
        }
    }

    public function destroy(Request $request, $id) {
        if (!Custom::checkForPermission($this->common_data['url_key'], 'delete')) {
            return redirect('admin/dashboard');
        }

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


    public function reorder(Request $request) {
        $url_key = $this->common_data['url_key'];
        $domain_slug = $request->query('domain_slug')  ?? self::DOMAIN_SLUG_TP;
        $query_param = '';
        if($domain_slug !== null && trim($domain_slug) !== '')
        {
           $query_param = '?domain_slug='.$domain_slug;
        }
        $this->common_data['module_urls']['list'] = route($url_key . '.index').$query_param;
        $data['admin_page_title'] = 'Reorder '. $this->singular_display_name. ' for '.Custom::getFullDomain($domain_slug);
        $data['founding_item_list'] = $this->modelObj->where('domain_slug', $domain_slug)->where('banner_for', self::FOUNDING_MEMBER)->orderBy('sort_order', 'ASC')->get();
        $data['official_item_list'] = $this->modelObj->where('domain_slug', $domain_slug)->where('banner_for', self::OFFICIAL_MEMBER)->orderBy('sort_order', 'ASC')->get();
        $data['recommended_item_list'] = $this->modelObj->where('domain_slug', $domain_slug)->where('banner_for', self::RECOMMENDED_COMPANY)->orderBy('sort_order', 'ASC')->get();
        $data['certifiedpro_item_list'] = $this->modelObj->where('domain_slug', $domain_slug)->where('banner_for', self::CERTIFIED_PRO)->orderBy('sort_order', 'ASC')->get();
        View::share($this->common_data);
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
}

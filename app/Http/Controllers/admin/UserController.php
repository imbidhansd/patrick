<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Validator;
use Str;
use Auth;
use Hash;
// Models [start]
use App\Models\Custom;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller {

    public function __construct() {
        $segment = \Request::segment(2);
        if ($segment == 're-order') {
            $segment = \Request::segment(3);
        }

        $url_key = $segment;
        $module_display_name = Str::singular(ucwords(str_replace('_', ' ', $segment)));

        // Links
        if (!in_array($segment, ['profile', 'update-profile', 'change-password'])) {
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
                'user_roles' => Role::pluck('name', 'id'),
            ]);

            // View
            $this->view_base = 'admin.' . $url_key;
        } else {
            // View
            $this->view_base = 'admin.users';
        }
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
        $data = ['admin_page_title' => 'Create ' . $this->singular_display_name];
        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users,email',
                    'username' => 'required|unique:users,username',
                    'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect($this->urls['add'])
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();

            /*/ initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');
            // generate a new secret key for the user
            $requestArr['google2fa_secret'] = $google2fa->generateSecretKey();*/
            $itemObj = $this->modelObj->create($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'post');
                $itemObj->media_id = $imageArr['mediaObj']->id;
            }
            $itemObj->save();

            // Assign User Role
            $role = Role::find($requestArr['role_id']);
            $itemObj->syncRoles([$role->name]);

            flash($this->module_messages['add'])->success();
            return redirect($this->urls['list']);
        }
    }

    public function edit($id) {
        $formObj = $this->modelObj->findOrFail($id);
        $data['admin_page_title'] = 'Edit ' . $this->singular_display_name;
        $data['formObj'] = $formObj;
        return view($this->view_base . '.edit', $data);
    }

    public function update($id, Request $request) {
        $itemObj = $this->modelObj->findOrFail($id);
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users,email,' . $id . ',id',
                    'username' => 'required|unique:users,username,' . $id . ',id',
                    'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect(route($this->urls['edit'], [$this->urls['url_key_singular'] => $id]))
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();
            $itemObj->update($requestArr);

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'user');
                $itemObj->media_id = $imageArr['mediaObj']->id;
            }
            $itemObj->save();

            // Assign User Role
            $role = Role::find($requestArr['role_id']);
            $itemObj->syncRoles([$role->name]);

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

    public function profile() {
        $data['user'] = Auth::user();
        $data['admin_page_title'] = 'Edit Profile';
        return view($this->view_base . '.profile', $data);
    }

    public function updateProfile(Request $request) {
        $formObj = Auth::user();
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect('admin/profile')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            $requestArr = $request->all();

            if ($request->hasFile('media')) {
                $imageArr = Custom::uploadFile($request->file('media'), 'page_banner', 'APP_IMAGE_THUMB_SIZES');
                $requestArr['media_id'] = $imageArr['mediaObj']->id;
            }

            $formObj->update($requestArr);

            // Activity Log
            //activity()->performedOn(Auth::user())->log('Updated Profile');

            session()->flash('success_message', 'Profile has been updated.');
            return redirect('admin/profile');
        }
    }

    public function changePassword() {
        $data['admin_page_title'] = 'Change Password';
        return view($this->view_base . '.change-password', $data);
    }

    public function updatePassword(Request $request) {
        //dd($request->all());
        $id = Auth::user()->id;
        $formObj = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
                    'old_password' => 'required|min:6|max:50',
                    'new_password' => 'required|min:6|max:50',
                    'confirm_password' => 'required|min:6|max:50|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect('admin/change-password')
                            ->withErrors($validator)
                            ->withInput();
        } else {
            // check for old password
            if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
                return redirect('admin/change-password')
                                ->withErrors(['old_password' => 'Old password do not matched!'])
                                ->withInput();
            }

            if ((Hash::check($request->get('new_password'), Auth::user()->password))) {
                return redirect('admin/change-password')
                                ->withErrors(['old_password' => 'You need to set a different password then your current password'])
                                ->withInput();
            }

            $formObj->update(['password' => bcrypt($request->get('new_password'))]);


            // Activity Log
            activity()->performedOn(Auth::user())->log('Changed Password');
            flash('Password has been changed.')->success();
            Auth::logout();
            return redirect('admin/login');
        }
    }

    // change other users password

    public function changeUserPassword(Request $request) {
        $user = User::find($request->get('user_id'));
        if ($user) {
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return ['status' => 1, 'message' => 'Password has been changed'];
        } else {
            return ['status' => 0, 'message' => 'User not found'];
        }
    }

    public function send2faAuthLink($id) {
        $userObj = $this->modelObj->findOrFail($id);

        // initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // generate a new secret key for the user
        $userObj->google2fa_secret = $google2fa->generateSecretKey();
        $userObj->save();

        // Send Email Here

        flash('Link has been sent to setup Google 2FA to ' . $userObj->email)->success();
        return back();
    }

}

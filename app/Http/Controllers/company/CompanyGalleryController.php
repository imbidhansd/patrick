<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Company\CompanyMail;
use App\Mail\Admin\AdminMail;
use Illuminate\Support\Facades\Mail;
use View;
use Validator;
use Str;
use Auth;
use Image;
use ImageOptimizer;
use App\Models\Media;
use App\Models\Company;
use App\Models\Custom;

class CompanyGalleryController extends Controller {

    public function __construct() {
        $this->web_settings = Custom::getSettings();
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

        // post type
        $this->post_type = $url_key;

        // Module Message
        $this->module_messages = Custom::getModuleFlashMessages($module_display_name);

        // Singular and Plural Name of Module
        $this->singular_display_name = Str::singular($module_display_name);
        $this->module_plural_name = 'Photo Gallery';

        $this->common_data = [
            'module_singular_name' => 'Photo Gallery',
            //'module_plural_name' => 'Photo Galleries',
            'url_key' => $url_key,
            'module_urls' => $this->urls,
        ];

        View::share($this->common_data);

        $this->view_base = 'company.company_galleries.';
    }

    public function index(Request $request) {
        $company_id = Auth::guard('company_user')->user()->company_id;

        $data = [
            'company_detail' => Company::find($company_id),
            'company_gallery_list' => $this->modelObj->where('company_id', $company_id)->order()->get()
        ];

        return view($this->view_base . 'index', $data);
    }

    public function create() {
        $company_id = Auth::guard('company_user')->user()->company_id;
        $company_gallery_count = $this->modelObj->where('company_id', $company_id)->count();

        if ($company_gallery_count == 10) {
            flash('Your gallery is full.')->warning();
            return back();
        }

        $data = ['admin_page_title' => 'Upload Photos'];

        return view($this->view_base . '.create', $data);
    }

    public function store(Request $request) {
        if ($request->has('gallery_type') && $request->get('gallery_type') == 'image') {
            $validator = Validator::make($request->all(), [
                        'file' => 'required',
            ]);
        } else if ($request->has('gallery_type') && $request->get('gallery_type') == 'video') {
            $validator = Validator::make($request->all(), [
                        'video_type' => 'required',
                            //'video_id' => 'required',
            ]);
        } else {
            flash('Select Gallery Type first.')->error();
            return redirect($this->urls['add'])->withInput();
        }


        if (isset($validator) && $validator->fails()) {
            return redirect($this->urls['add'])->withErrors($validator)->withInput();
        } else {
            $company_id = Auth::guard('company_user')->user()->company_id;
            $company_gallery_count = $this->modelObj->where('company_id', $company_id)->count();

            if ($company_gallery_count == 10) {
                flash('Your gallery is full.')->warning();
                return back();
            }

            $requestArr = $request->all();
            if ($requestArr['gallery_type'] == 'image') {
                $other_images = $request->file('file');
                $plus_counter = count($other_images);
            } else {
                $plus_counter = 1;
            }

            $companyUserObj = Auth::guard('company_user')->user();
            $company_id = Auth::guard('company_user')->user()->company_id;
            $companyObj = Company::find($company_id);

            $total_images = $company_gallery_count + $plus_counter;
            if ($total_images > 10) {
                flash('You can add 10 photos maximum into your gallery')->warning();
                return back();
            }


            if ($requestArr['gallery_type'] == 'image') {
                if ($plus_counter > 10) {
                    flash('You can add 10 photos maximum into your gallery')->warning();
                    return back();
                } else if ($plus_counter > 0) {
                    foreach ($other_images as $file) {
                        $mediaObj = Custom::uploadFile($file, $this->post_type);

                        $this->modelObj->create([
                            'company_id' => $company_id,
                            'media_id' => $mediaObj['mediaObj']->id,
                        ]);
                    }
                }
            } else if ($requestArr['gallery_type'] == 'video') {
                $requestArr['company_id'] = $company_id;
                if ($requestArr['video_type'] == 'youtube') {
                    $requestArr['video_id'] = $requestArr['youtube_video_id'];
                    $requestArr['image_link'] = 'http://img.youtube.com/vi/' . $requestArr['youtube_video_id'] . '/hqdefault.jpg';
                } else if ($requestArr['video_type'] == 'vimeo') {
                    $requestArr['video_id'] = $requestArr['vimeo_video_id'];
                    $requestArr['image_link'] = Custom::get_vimeo_thumb($requestArr['vimeo_video_id'], 'medium');
                }


                $file_name = $requestArr['video_id'] . '.jpg';
                $file_path = 'uploads/media/' . $file_name;
                copy($requestArr['image_link'], $file_path);

                $mediaObj = Media::create([
                            'file_name' => $file_name,
                            'original_file_name' => $file_name,
                            'file_type' => 'image/jpg',
                            'file_extension' => 'jpg',
                ]);

                $requestArr['media_id'] = $mediaObj->id;

                // Optimize Image
                ImageOptimizer::optimize($file_path, $file_path);
                $image_obj = Image::make($file_path);

                if (env('FIT_THUMBS') != '') {
                    Custom::createThumbnails($file_path, 'fit_thumbs', $file_name, env('FIT_THUMBS'));
                }
                if (env('HEIGHT_THUMBS') != '') {
                    Custom::createThumbnails($file_path, 'height_thumbs', $file_name, env('HEIGHT_THUMBS'));
                }
                if (env('WIDTH_THUMBS') != '') {
                    Custom::createThumbnails($file_path, 'width_thumbs', $file_name, env('WIDTH_THUMBS'));
                }

                $this->modelObj->create($requestArr);
            }
        }

        /* Company Bio change mail to Company */
        $web_settings = $this->web_settings;
        $company_mail_id = "112"; /* Mail Title: Company Gallery Update */
        $replaceWithArr = [
            'company_name' => $companyObj->company_name,
            'phone_number' => ((isset($web_settings['phone_number']) && $web_settings['phone_number'] != '') ? $web_settings['phone_number'] : ''),
            'global_domain' => ((isset($web_settings['global_domain']) && $web_settings['global_domain'] != '') ? $web_settings['global_domain'] : ''),
            'global_address' => ((isset($web_settings['office_address']) && $web_settings['office_address'] != '') ? $web_settings['office_address'] : ''),
            'global_email' => ((isset($web_settings['global_email']) && $web_settings['global_email'] != '') ? $web_settings['global_email'] : ''),
            'facebook_link' => ((isset($web_settings['facebook']) && $web_settings['facebook'] != '') ? $web_settings['facebook'] : ''),
            'twitter_link' => ((isset($web_settings['twitter']) && $web_settings['twitter'] != '') ? $web_settings['twitter'] : ''),
            'instagram_link' => ((isset($web_settings['instagram']) && $web_settings['instagram'] != '') ? $web_settings['instagram'] : ''),
            'rumble_link' => ((isset($web_settings['rumble']) && $web_settings['rumble'] != '') ? $web_settings['rumble'] : ''),
            'youtube_link' => ((isset($web_settings['youtube']) && $web_settings['youtube'] != '') ? $web_settings['youtube'] : ''),
            'unsubscription_link' => url('unsubscribe-page/company', ['company_slug' => $companyObj->slug]),
            'request_generate_link' => $companyUserObj->email,
            'date' => $companyObj->created_at->format(env('DATE_FORMAT')),
            'url' => url('company_galleries'),
            'email_footer' => $companyUserObj->email,
            'copyright_year' => date('Y'),
                //'main_service_category' => '',
        ];

        $messageArr = [
            'company_id' => $companyObj->id,
            'message_type' => 'info',
            'link' => url('company_galleries')
        ];
        Custom::companyMailMessageCreate($messageArr, $company_mail_id, $replaceWithArr);
        $mailArr = Custom::generate_company_user_email_arr($companyObj->company_information);
        if (!is_null($mailArr) && count($mailArr) > 0) {
            foreach ($mailArr AS $mail_item) {
                Mail::to($mail_item)->send(new CompanyMail($company_mail_id, $replaceWithArr));
            }
        }


        /* Company Bio change mail to Admin */
        if (isset($this->web_settings['global_email']) && $this->web_settings['global_email'] != '') {
            $admin_mail_id = "113"; /* Mail Title: Company Gallery Update - Admin */
            $adminReplaceWithArr = [
                'company_name' => $companyObj->company_name,
            ];

            Mail::to($this->web_settings['global_email'])->send(new AdminMail($admin_mail_id, $adminReplaceWithArr));
        }

        flash('File(s) uploaded successfully')->success();
        return redirect($this->urls['list']);
    }

    public function destroy($id) {
        $modelObj = $this->modelObj->findOrFail($id);

        try {
            /* get media id */
            $mediaObj = Media::find($modelObj->media_id);
            if (!is_null($mediaObj)) {
                Custom::delete_media($mediaObj);
            }

            $modelObj->delete();
            flash($this->module_messages['delete'])->success();
            return back();
        } catch (Exception $e) {
            flash($this->module_messages['delete_error'])->danger();
            return back();
        }
    }

    public function update_status(Request $request) {
        if (in_array($request->get('action'), ['delete'])) {
            /* get media ids */
            $media_ids = $this->modelObj->whereIn('id', $request->get('ids'))->pluck('media_id')->toArray();
            if (count($media_ids) > 0) {
                foreach ($media_ids AS $media_item) {
                    $mediaObj = Media::find($media_item);
                    if (!is_null($mediaObj)) {
                        Custom::delete_media($mediaObj);
                    }
                }
            }

            $this->modelObj->whereIn('id', $request->get('ids'))->delete();
            flash('Row(s) has been deleted successfully')->success();
        }

        return back();
    }

    public function reorder() {
        $company_id = Auth::guard('company_user')->user()->company_id;

        $data['admin_page_title'] = 'Reorder Photos';
        $data['galleries'] = $this->modelObj->with('media')
                ->where([
                    ['company_id', $company_id],
                    ['status', '!=', 'rejected']
                ])
                ->order()
                ->get();

        return view($this->view_base . '.reorder', $data);
    }

    public function updateOrder(Request $request) {
        $image_order = $request->get('image_order');
        if (count($image_order) > 0) {
            $order = 1;
            foreach ($image_order as $item) {
                $galleryObj = $this->modelObj->find($item);
                $galleryObj->sort_order = $order++;
                $galleryObj->save();
            }
        }
    }

}

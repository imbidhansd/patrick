<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller {

    public function __construct() {
        $this->view_base = 'company.news.';
    }

    public function index(Request $request) {
        $data = [
            'admin_page_title' => 'News',
            'news' => News::active()->order()->paginate(env('APP_RECORDS_PER_PAGE'))
        ];
        return view($this->view_base . 'index', $data);
    }

}

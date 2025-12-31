<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware {

    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'admin/media/editorupload',
        /* API requests start */
        /* 2-3-2020 start */
        'api/*',
        /* 2-3-2020 end */
        /*'api/find-a-pro/get-trades',
        'api/find-a-pro/get-service-category-types',
        'api/find-a-pro/get-top-level-categories',
        'api/find-a-pro/get-main-categories',
        'api/find-a-pro/get-service-categories',*/
        /* 5-2-2020 Apis start */
        /*'api/find-a-pro/maincategories',
        'api/find-a-pro/trades',
        'api/find-a-pro/servicecategories',
        'api/find-a-pro/timeframes',
        'api/find-a-pro',
        'api/verify-a-member'*/
        /* API requests end */
    ];

}

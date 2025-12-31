<?php

namespace App\Http\Middleware;

use DB;
use App\Models\Trade;
use App\Models\MembershipLevel;
use Closure;

class AdminCommonMiddleware {

    public function handle($request, Closure $next) {
        $sidebar_trades = Trade::active()->order()->pluck('short_name', 'id');

        $modules = \App\Models\Module::order()->get();
        $module_with_permissions = [];
        if (count($modules) > 0) {
            foreach ($modules as $module_item) {
                $module_with_permissions[$module_item->name] = json_decode($module_item->permission_options);
            }
        }

        $membership_levels = MembershipLevel::withCount('companies')->get();
        //dd($membership_levels);
        // to share data in all controllser
        config(['module_with_permissions' => $module_with_permissions]);

        // to share data in all views
        \View::share([
            'module_with_permissions' => $module_with_permissions,
            'top_menu_membership_levels' => $membership_levels,
            'sidebar_trades' => $sidebar_trades,
        ]);

        return $next($request);
    }

}

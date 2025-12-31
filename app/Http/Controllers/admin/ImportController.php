<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
// Models [start]
use App\Models\Trade;
use App\Models\TopLevelCategory;
use App\Models\MainCategory;
use App\Models\MainCategoryTopLevelCategory;
use App\Models\ServiceCategory;
use App\Models\ServiceCategoryType;
use App\Models\TopLevelCategoryTrade;

class ImportController extends Controller {

    //
    public function import(Request $request) {

        // Delete All From Categories and Table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //DB::table('datapoints')->truncate();
        //DB::table('sensors')->truncate();

        Trade::truncate();

        TopLevelCategory::truncate();
        TopLevelCategoryTrade::truncate();

        MainCategory::truncate();
        MainCategoryTopLevelCategory::truncate();

        ServiceCategoryType::truncate();
        ServiceCategory::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // 1. Trades
        $old_trades = DB::connection('mysql2')->table('trades')->get();
        if (!is_null($old_trades)) {
            foreach ($old_trades as $old_trade_item) {

                Trade::create([
                    'old_id' => $old_trade_item->id,
                    'title' => $old_trade_item->trade_name,
                ]);
            }
        }

        // 2 Top Level Categories
        $old_top_level_categories = DB::connection('mysql2')->table('top_level_categories')->get();
        if (!is_null($old_top_level_categories)) {
            foreach ($old_top_level_categories as $old_top_level_category_item) {
                TopLevelCategory::create([
                    'old_id' => $old_top_level_category_item->id,
                    'title' => $old_top_level_category_item->category_name,
                    'tlc_id' => $old_top_level_category_item->sc_id,
                ]);
            }


            // 2.1 Top Leval Categories With Trade
            $old_tlc_trades = DB::connection('mysql2')->table('tlc_trade')->get();

            if (!is_null($old_tlc_trades)) {
                foreach ($old_tlc_trades as $old_tlc_trade_item) {

                    // Find New Top Level Category Item
                    $new_top_level_category_item = TopLevelCategory::where('old_id', $old_tlc_trade_item->tlc_id)->first();

                    if (!is_null($new_top_level_category_item)) {
                        TopLevelCategoryTrade::create([
                            'trade_id' => $old_tlc_trade_item->trade_id,
                            'top_level_category_id' => $new_top_level_category_item->id,
                        ]);
                    }
                }
            }
        }


        // 3 Main Categories
        $old_main_categories = DB::connection('mysql2')->table('main_categories')->get();
        if (!is_null($old_main_categories)) {
            foreach ($old_main_categories as $old_main_category_item) {

                MainCategory::create([
                    'old_id' => $old_main_category_item->id,
                    'title' => $old_main_category_item->main_category_name,
                    'abbr' => $old_main_category_item->abbreviation,
                ]);
            }


            // 3.1 Main Categories With Top Level Categories
            $old_main_category_top_level_categories = DB::connection('mysql2')->table('main_category_top_level_category')->get();

            if (!is_null($old_main_category_top_level_categories)) {
                foreach ($old_main_category_top_level_categories as $old_main_category_top_level_category_item) {

                    // Find New Top Level Category Item
                    $new_top_level_category_item = TopLevelCategory::where('old_id', $old_main_category_top_level_category_item->tlc_id)->first();

                    // Find New Main Category Item
                    $new_main_category_item = MainCategory::where('old_id', $old_main_category_top_level_category_item->main_category_id)->first();

                    if (!is_null($new_top_level_category_item) && !is_null($new_main_category_item)) {
                        MainCategoryTopLevelCategory::create([
                            'top_level_category_id' => $new_top_level_category_item->id,
                            'main_category_id' => $new_main_category_item->id,
                        ]);
                    }
                }
            }
        }

        // 4 Service Category Type
        $old_service_category_types = DB::connection('mysql2')->table('service_category_types')->get();
        if (!is_null($old_service_category_types)) {
            foreach ($old_service_category_types as $old_service_category_type_item) {

                ServiceCategoryType::create([
                    'title' => $old_service_category_type_item->category_type_name,
                ]);
            }
        }

        // 5 Service Categorycd
        $old_service_categories = DB::connection('mysql2')->table('hseforms_category')->get();
        if (!is_null($old_service_categories)) {
            foreach ($old_service_categories as $old_service_category_item) {

                // Find new main Category
                $new_main_category_item = MainCategory::where('old_id', $old_service_category_item->main_categories)->first();

                if (!is_null($new_main_category_item)) {
                    // Find Top Level Category
                    $main_category_top_level_category_item = MainCategoryTopLevelCategory::where('main_category_id', $new_main_category_item->id)->first();

                    if (!is_null($main_category_top_level_category_item)) {
                        ServiceCategory::create([
                            'title' => $old_service_category_item->category_name,
                            'abbr' => $old_service_category_item->category_abbreviation,
                            'top_level_category_id' => $main_category_top_level_category_item->top_level_category_id,
                            'main_category_id' => $new_main_category_item->id,
                            'service_category_type_id' => $old_service_category_item->service_category_type,
                        ]);
                    }
                }
            }
        }




        dd('End Script');
    }

}

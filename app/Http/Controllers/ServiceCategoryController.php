<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller {

    public function update_service_category() {
        $service_categories = ServiceCategory::with(['top_level_category', 'service_category_type'])
                ->orderBy('top_level_category_id', 'ASC')
                ->orderBy('service_category_type_id', 'ASC')
                ->orderBy('main_category_id', 'ASC')
                ->get();

        if (count($service_categories) > 0) {
            $top_level_category_id = $main_category_id = $service_category_type_id = '';
            $counter = 0;
            foreach ($service_categories AS $service_category_item) {
                if ($top_level_category_id != $service_category_item->top_level_category_id) {
                    $top_level_category_id = $service_category_item->top_level_category_id;
                    $counter = 0;
                }

                if ($main_category_id != $service_category_item->main_category_id) {
                    $main_category_id = $service_category_item->main_category_id;
                    $counter = 0;
                }

                if ($service_category_type_id != $service_category_item->service_category_type_id) {
                    $service_category_type_id = $service_category_item->service_category_type_id;
                    $counter = 0;
                }

                $service_category_id = $service_category_item->top_level_category->tlc_id . '-' . $service_category_item->main_category_id;

                if ($service_category_item->service_category_type_id == 1) {
                    $service_category_id .= 'R';
                } else if ($service_category_item->service_category_type_id == 2) {
                    $service_category_id .= 'C';
                }

                $service_category_id .= '-' . ($counter + 1);


                $service_category_item->service_category_id = $service_category_id;
                $service_category_item->save();

                $counter++;
                echo $service_category_id . ' <br />';
            }
        }
        echo "Service category updated successfully.";
    }

}

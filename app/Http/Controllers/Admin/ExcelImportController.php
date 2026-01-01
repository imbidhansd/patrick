<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\NetworxTask;

class ExcelImportController extends Controller {

    public function networx_import() {
        $file_name = 'uploads' . DIRECTORY_SEPARATOR . 'nx_task_list_2020_05_06_11_32.xlsx';

        $import = (new FastExcel)->import($file_name, function ($line) {
            $insertArr = [
                'task_name' => $line['Task Name'],
                'task_id' => $line['Task ID']
            ];

            NetworxTask::create($insertArr);
        });

        echo 'Networx imported successfully.';
        exit();
    }

}

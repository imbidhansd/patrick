<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyCompanyInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify default for created_at and updated_at columns
        DB::statement('
            ALTER TABLE company_invoice_items
            MODIFY created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            MODIFY updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the changes if needed
        DB::statement('
            ALTER TABLE company_invoice_items
            MODIFY created_at DATETIME DEFAULT NULL,
            MODIFY updated_at DATETIME DEFAULT NULL
        ');
    }
}

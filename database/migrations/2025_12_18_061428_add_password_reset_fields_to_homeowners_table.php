<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordResetFieldsToHomeownersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeowners', function (Blueprint $table) {
            $table->string('password_reset_otp', 10)->nullable();
            $table->timestamp('password_reset_otp_expires_at')->nullable();
            $table->string('password_reset_token', 255)->nullable();
            $table->timestamp('password_reset_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeowners', function (Blueprint $table) {
            $table->dropColumn(['password_reset_otp', 'password_reset_otp_expires_at', 'password_reset_token', 'password_reset_token_expires_at']);
        });
    }
}

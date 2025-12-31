<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeownersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeowners', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 25);
            $table->string('password', 255);
            $table->string('address_line_1', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 25)->nullable();
            
            // Verification fields
            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->string('email_otp', 10)->nullable();
            $table->string('phone_otp', 10)->nullable();
            $table->timestamp('email_otp_expires_at')->nullable();
            $table->timestamp('phone_otp_expires_at')->nullable();
            
            // Status and tracking
            $table->timestamp('last_login_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('inactive');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeowners');
    }
}

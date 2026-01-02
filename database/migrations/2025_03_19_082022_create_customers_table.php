<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('camp_id');
            $table->string('fullname');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('username');
            $table->string('password');
            $table->string('mac_address')->nullable();
            $table->tinyInteger('status');
            $table->dateTime('login_datetime')->nullable();
            $table->dateTime('expiry_datetime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->foreignId('camp_id');
            $table->foreignId('user_id');
            $table->foreignId('package_id');
            $table->date('purchase_date');
            $table->morphs('invoiceable');
            $table->foreignId('paymethod_id');
            $table->datetime('login_datetime')->nullable();
            $table->datetime('expire_datetime')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->decimal('price', 10, 2);
            $table->smallInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

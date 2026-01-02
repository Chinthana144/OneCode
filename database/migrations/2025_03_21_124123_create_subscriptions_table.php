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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('camp_id');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('package_id');
            $table->integer('paymethod_id');
            $table->date('purchaseDate');
            $table->datetime('purchaseDateTime');
            $table->datetime('subscriptionStartTime')->nullable();
            $table->datetime('subscriptionEndTime')->nullable();
            $table->decimal('price');
            $table->string('macAddress');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

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
        Schema::create('access_planes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id');
            $table->foreignId('user_id');
            $table->foreignId('package_id');
            $table->foreignId('paymethod_id');
            $table->morphs('accessable');
            $table->date('purchaseDate');
            $table->datetime('purchaseDateTime');
            $table->datetime('login_at')->nullable();
            $table->datetime('expire_at')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->decimal('price');
            $table->smallInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_planes');
    }
};

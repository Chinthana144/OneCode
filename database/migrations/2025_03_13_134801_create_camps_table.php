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
        Schema::create('camps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('contactPerson');
            $table->string('contactPhone');
            $table->string('contactEmail');
            $table->string('mikritikIP');
            $table->string('mikritikPort');
            $table->string('mikrotikUsername');
            $table->string('mikrotikPassword');
            $table->string('radiusSecret');
            $table->string('radiusIP');
            $table->decimal('monthly_target', 10, 2)->default(0.00);
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camps');
    }
};

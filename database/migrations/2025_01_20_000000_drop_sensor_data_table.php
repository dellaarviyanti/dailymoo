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
        Schema::dropIfExists('sensor_data');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->decimal('feed_weight', 8, 2);
            $table->timestamps();
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feeding_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session')->default('pagi');
            $table->string('feed_type')->nullable();
            $table->float('offered_amount')->default(0);
            $table->float('consumed_amount')->default(0);
            $table->float('cow_weight')->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeding_logs');
    }
};


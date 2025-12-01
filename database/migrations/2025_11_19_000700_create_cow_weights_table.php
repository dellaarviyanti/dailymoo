<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cow_weights', function (Blueprint $table) {
            $table->id();
            $table->integer('cow_id')->comment('Nomor sapi (1-10)');
            $table->decimal('weight', 8, 2)->comment('Bobot dalam kg');
            $table->date('measured_at')->comment('Tanggal pengukuran');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->timestamps();

            $table->index(['cow_id', 'measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cow_weights');
    }
};


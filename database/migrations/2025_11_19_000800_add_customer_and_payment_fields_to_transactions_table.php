<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns already exist to avoid errors
        if (!Schema::hasColumn('transactions', 'customer_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('customer_name')->nullable()->after('user_id');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'customer_address')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->text('customer_address')->nullable()->after('customer_name');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'customer_phone')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('customer_phone')->nullable()->after('customer_address');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'payment_proof')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('payment_proof')->nullable()->after('status');
            });
        }
        
        if (!Schema::hasColumn('transactions', 'bank_account')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('bank_account')->nullable()->after('payment_proof');
            });
        }
        
        // Update enum status (safer approach)
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'pending_payment', 'payment_verification', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_address', 'customer_phone', 'payment_proof', 'bank_account']);
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};


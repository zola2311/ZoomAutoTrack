<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->unique();
            $table->foreignId('referred_by_customer_id')->nullable()
                ->constrained('customers')->nullOnDelete();
            $table->timestamp('referral_bonus_awarded_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by_customer_id');
            $table->dropColumn(['referral_code', 'referral_bonus_awarded_at']);
        });
    }
};

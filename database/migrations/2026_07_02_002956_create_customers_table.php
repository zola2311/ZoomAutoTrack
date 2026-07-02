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
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();

            $table->string('customer_code')->unique();
            $table->string('type')->default('individual');

            $table->string('full_name')->nullable();
            $table->string('company_name')->nullable();

            $table->string('phone')->index();
            $table->string('secondary_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tin_number')->nullable();

            $table->text('address')->nullable();
            $table->string('preferred_language')->default('en');
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->text('notes')->nullable();
            $table->softDeletes();
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

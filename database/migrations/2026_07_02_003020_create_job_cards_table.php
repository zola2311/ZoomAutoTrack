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
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();

            $table->foreignId('service_advisor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('mechanic_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('job_number')->unique();
            $table->unsignedInteger('mileage_at_checkin')->nullable();
            $table->string('fuel_level')->nullable();

            $table->text('customer_complaint')->nullable();
            $table->foreignId('customer_complaint_voice_id')->nullable();

            $table->text('mechanic_notes')->nullable();
            $table->foreignId('mechanic_notes_voice_id')->nullable();

            $table->string('status')->default('checked_in')->index();
            $table->string('priority')->default('normal')->index();

            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('estimated_completion_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};

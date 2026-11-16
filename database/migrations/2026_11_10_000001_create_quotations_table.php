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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();

            // Customer Contact Information
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city')->nullable();

            // Vehicle Specifications
            $table->string('make');
            $table->string('model');
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('plate_number')->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->string('transmission')->nullable(); // Automatic, Manual, CVT, Other
            $table->string('fuel_type')->nullable(); // Petrol, Diesel, Hybrid, Electric
            $table->string('vin')->nullable();

            // Service & Defect Questionnaire
            $table->string('service_category'); // e.g. Periodic Maintenance, Engine & Diagnostics, Brakes, Suspension, AC & Electrical, Body & Collision, Other
            $table->json('symptoms')->nullable(); // JSON array of selected symptoms
            $table->text('description'); // Detailed explanation from customer
            $table->string('drivable_status')->default('drivable'); // drivable, drivable_with_care, not_drivable
            $table->string('urgency')->default('flexible'); // urgent_asap, within_week, flexible
            $table->date('preferred_date')->nullable();

            // Status Workflow: pending -> in_review -> quoted -> accepted -> declined -> converted_to_job
            $table->string('status')->default('pending')->index();

            // Admin Quotation Response & Estimation
            $table->text('diagnostic_assessment')->nullable(); // "What works will be done", repair scope, recommendations
            $table->json('labor_items')->nullable(); // array of [['description' => '', 'estimated_cost' => 0]]
            $table->json('parts_items')->nullable(); // array of [['part_name' => '', 'quantity' => 1, 'estimated_cost' => 0, 'total' => 0]]
            $table->decimal('estimated_labor_total', 12, 2)->default(0);
            $table->decimal('estimated_parts_total', 12, 2)->default(0);
            $table->decimal('estimated_tax', 12, 2)->default(0);
            $table->decimal('estimated_discount', 12, 2)->default(0);
            $table->decimal('estimated_total', 12, 2)->default(0);
            $table->string('estimated_duration')->nullable(); // e.g. "1-2 Business Days", "4 Hours"
            $table->date('valid_until')->nullable(); // Quotation validity
            $table->text('admin_notes')->nullable(); // Internal notes
            $table->foreignId('quoted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamp('response_email_sent_at')->nullable();
            $table->string('customer_feedback')->nullable(); // accepted, declined

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};

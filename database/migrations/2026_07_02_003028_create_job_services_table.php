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
        Schema::create('job_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();

            $table->foreignId('assigned_mechanic_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('description');
            $table->decimal('labor_cost', 12, 2)->default(0);
            $table->string('status')->default('pending')->index();

            $table->text('notes')->nullable();
            $table->foreignId('voice_note_id')->nullable();

            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_services');
    }
};

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
        Schema::create('vehicle_inspection_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_inspection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspection_item_id')->constrained()->cascadeOnDelete();

            $table->string('result')->nullable();
            $table->text('value')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('voice_note_id')->nullable();
            $table->foreignId('photo_id')->nullable();

            $table->timestamps();

            $table->unique(['vehicle_inspection_id', 'inspection_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_inspection_answers');
    }
};

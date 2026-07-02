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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('part_number')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();

            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);

            $table->integer('quantity_on_hand')->default(0);
            $table->integer('minimum_stock')->default(0);

            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['branch_id', 'part_number']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

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
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Punjab", "Sindh", "All Pakistan"
            $table->decimal('rate', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->text('cities')->nullable(); // Comma separated list of cities, or null for all
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};

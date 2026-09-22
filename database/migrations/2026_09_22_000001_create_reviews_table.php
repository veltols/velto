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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5 stars
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('approved'); // approved, pending, rejected
            $table->boolean('is_featured')->default(false);
            $table->boolean('verified_purchase')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('rating');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

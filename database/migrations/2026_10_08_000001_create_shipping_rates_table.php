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
        if (Schema::hasTable('shipping_zones') && !Schema::hasTable('shipping_rates')) {
            Schema::rename('shipping_zones', 'shipping_rates');
            Schema::table('shipping_rates', function (Blueprint $table) {
                if (!Schema::hasColumn('shipping_rates', 'min_order_amount')) {
                    $table->decimal('min_order_amount', 10, 2)->nullable()->after('rate');
                }
                if (!Schema::hasColumn('shipping_rates', 'is_default')) {
                    $table->boolean('is_default')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('shipping_rates', 'notes')) {
                    $table->string('notes')->nullable()->after('cities');
                }
            });
        } elseif (!Schema::hasTable('shipping_rates')) {
            Schema::create('shipping_rates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('rate', 10, 2)->default(0);
                $table->decimal('min_order_amount', 10, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
                $table->text('cities')->nullable();
                $table->string('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shipping_rates')) {
            Schema::table('shipping_rates', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('shipping_rates', 'min_order_amount')) $columns[] = 'min_order_amount';
                if (Schema::hasColumn('shipping_rates', 'is_default')) $columns[] = 'is_default';
                if (Schema::hasColumn('shipping_rates', 'notes')) $columns[] = 'notes';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
            Schema::rename('shipping_rates', 'shipping_zones');
        }
    }
};

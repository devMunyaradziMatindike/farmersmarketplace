<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Volume & Unit Specifications
            $table->decimal('quantity', 10, 2)->nullable()->after('price');
            $table->string('unit', 50)->default('kg')->after('quantity');
            $table->decimal('minimum_order_quantity', 10, 2)->nullable()->after('unit');
            $table->string('packaging_type', 50)->nullable()->after('minimum_order_quantity'); // bags, crates, bulk, etc.

            // Seller Metrics (for ranking)
            $table->integer('views_count')->default(0)->after('status');
            $table->integer('inquiries_count')->default(0)->after('views_count');
            $table->decimal('seller_rating', 3, 2)->nullable()->after('inquiries_count');
            $table->integer('seller_successful_sales')->default(0)->after('seller_rating');

            // Freshness & Availability
            $table->date('harvest_date')->nullable()->after('seller_successful_sales');
            $table->date('expiry_date')->nullable()->after('harvest_date');
            $table->boolean('is_perishable')->default(false)->after('expiry_date');

            // Bulk/Specialized Filters
            $table->boolean('is_bulk_available')->default(false)->after('is_perishable');
            $table->boolean('wholesale_only')->default(false)->after('is_bulk_available');
            $table->string('season', 50)->nullable()->after('wholesale_only'); // rainy, dry, etc.

            // Indexes for performance
            $table->index(['quantity', 'unit']);
            $table->index('views_count');
            $table->index('is_bulk_available');
            $table->index('wholesale_only');
        });

        // Add full-text index only for MySQL/MariaDB (not supported in SQLite)
        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            try {
                DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_fulltext_idx (name, description)');
            } catch (\Exception $e) {
                // Index might already exist or table might not support it
                // Silently fail - the LIKE search fallback will work
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['quantity', 'unit']);
            $table->dropIndex(['views_count']);
            $table->dropIndex(['is_bulk_available']);
            $table->dropIndex(['wholesale_only']);

            // Drop columns
            $table->dropColumn([
                'quantity',
                'unit',
                'minimum_order_quantity',
                'packaging_type',
                'views_count',
                'inquiries_count',
                'seller_rating',
                'seller_successful_sales',
                'harvest_date',
                'expiry_date',
                'is_perishable',
                'is_bulk_available',
                'wholesale_only',
                'season',
            ]);
        });
    }
};

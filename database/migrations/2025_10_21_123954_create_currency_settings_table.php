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
        Schema::create('currency_settings', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency')->default('USD');
            $table->decimal('zwg_to_usd_rate', 10, 4)->default(13.5000);
            $table->timestamp('rate_updated_at')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('currency_settings')->insert([
            'base_currency' => 'USD',
            'zwg_to_usd_rate' => 13.5000,
            'rate_updated_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_settings');
    }
};

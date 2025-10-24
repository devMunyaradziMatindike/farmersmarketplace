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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->unique()->nullable()->after('email');
            $table->enum('auth_method', ['phone', 'google', 'email'])->default('phone')->after('phone_number');
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('auth_method');
            $table->string('google_id')->unique()->nullable()->after('role');
            $table->string('otp', 6)->nullable()->after('password');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'auth_method', 'role', 'google_id', 'otp', 'otp_expires_at']);
        });
    }
};

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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['workshop', 'exhibition', 'training', 'seminar', 'field_day', 'conference', 'networking', 'other']);
            $table->string('image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_registration_open')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->text('registration_instructions')->nullable();
            $table->string('organizer_name');
            $table->string('organizer_contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

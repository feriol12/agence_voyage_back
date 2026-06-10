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
        // database/migrations/xxxx_create_trips_table.php
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('reference')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_days');
            $table->integer('capacity');
            $table->decimal('base_price', 10, 2);
            $table->enum('status', ['disponible', 'complet', 'ferme'])->default('disponible');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

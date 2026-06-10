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
        // database/migrations/xxxx_create_reminders_table.php
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_trip_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['document_missing', 'payment_due', 'step_overdue', 'departure_soon']);
            $table->date('scheduled_date');
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};

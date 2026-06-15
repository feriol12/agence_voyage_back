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
           Schema::table('client_trips', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                  ->default('pending')
                  ->after('assigned_at'); // place le champ après assigned_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('client_trips', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

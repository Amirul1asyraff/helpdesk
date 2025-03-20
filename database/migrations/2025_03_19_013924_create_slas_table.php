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
        Schema::create('slas', function (Blueprint $table) {
            $table->id();
            $table->string('status'); // SLA status level (e.g., Critical, High, Medium, Low)
            $table->integer('response_time'); // Time in minutes for initial response
            $table->integer('resolution_time'); // Time in minutes for ticket resolution
            $table->decimal('penalty', 10, 2)->default(0.00); // Penalty for SLA breaches in percentage maximus number is   9999999999.99
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slas');
    }
};

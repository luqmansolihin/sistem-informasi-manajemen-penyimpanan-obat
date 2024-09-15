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
        Schema::create('medicine_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_medicine_id')->constrained('transaction_medicines')->cascadeOnDelete();
            $table->foreignId('transaction_patient_has_medicine_id')->constrained('transaction_patient_has_medicines')->cascadeOnDelete();
            $table->bigInteger('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_sales');
    }
};

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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade')->comment('معرف المريض');
            $table->date('appointment_date')->comment('تاريخ الموعد');
            $table->time('appointment_time')->comment('وقت الموعد');
            $table->decimal('amount', 10, 2)->nullable()->comment('المبلغ');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->comment('حالة الموعد');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

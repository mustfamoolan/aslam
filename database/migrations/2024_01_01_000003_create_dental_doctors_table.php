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
        Schema::create('dental_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم الطبيب');
            $table->foreignId('dental_specialty_id')->constrained()->comment('تخصص الطبيب');
            $table->foreignId('dental_clinic_id')->constrained()->comment('العيادة التابع لها');
            $table->string('phone')->unique()->comment('رقم الهاتف للتواصل وتسجيل الدخول');
            $table->string('email')->nullable()->comment('البريد الإلكتروني (اختياري)');
            $table->string('password')->comment('كلمة المرور');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_doctors');
    }
};

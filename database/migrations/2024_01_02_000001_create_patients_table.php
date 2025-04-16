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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dental_clinic_id')->constrained()->onDelete('cascade')->comment('معرف العيادة');
            $table->string('full_name')->comment('اسم المريض الثلاثي');
            $table->integer('age')->comment('عمر المريض');
            $table->enum('gender', ['male', 'female'])->comment('جنس المريض');
            $table->string('phone_number')->comment('رقم الهاتف');
            $table->string('occupation')->nullable()->comment('الوظيفة');
            $table->string('address')->nullable()->comment('محل السكن');
            $table->integer('patient_number')->comment('تسلسل المريض');
            $table->date('registration_date')->comment('تاريخ الإضافة');
            $table->time('registration_time')->comment('وقت الإضافة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
        });

        // جدول للأمراض المزمنة
        Schema::create('chronic_diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المرض المزمن');
            $table->timestamps();
        });

        // جدول للحساسية
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم الحساسية');
            $table->timestamps();
        });

        // جدول وسيط للربط بين المرضى والأمراض المزمنة
        Schema::create('patient_chronic_disease', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('chronic_disease_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // جدول وسيط للربط بين المرضى والحساسية
        Schema::create('patient_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('allergy_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_allergy');
        Schema::dropIfExists('patient_chronic_disease');
        Schema::dropIfExists('allergies');
        Schema::dropIfExists('chronic_diseases');
        Schema::dropIfExists('patients');
    }
};

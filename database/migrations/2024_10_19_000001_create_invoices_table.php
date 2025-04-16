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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dental_clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('invoice_type'); // نوع الفاتورة
            $table->date('issue_date'); // تاريخ إصدار الفاتورة
            $table->decimal('amount', 10, 2); // المبلغ الإجمالي
            $table->decimal('paid_amount', 10, 2)->default(0); // المبلغ المدفوع
            $table->decimal('remaining_amount', 10, 2)->default(0); // المبلغ المتبقي
            $table->string('session_title')->nullable(); // عنوان الجلسة
            $table->text('note')->nullable(); // ملاحظة
            $table->boolean('is_paid')->default(false); // هل تم دفع الفاتورة بالكامل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

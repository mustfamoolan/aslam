<?php

namespace Database\Migrations;

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dental_clinic_id')->constrained()->onDelete('cascade');
            $table->string('title'); // عنوان التنبيه
            $table->text('content'); // محتوى التنبيه
            $table->string('type')->default('info'); // نوع التنبيه (info, warning, danger)
            $table->boolean('is_read')->default(false); // هل تم قراءة التنبيه
            $table->timestamp('read_at')->nullable(); // وقت قراءة التنبيه
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

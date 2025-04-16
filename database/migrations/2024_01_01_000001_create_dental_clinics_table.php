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
        Schema::create('dental_clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم العيادة');
            $table->text('address')->comment('عنوان العيادة');
            $table->time('opening_time')->comment('وقت بدء الدوام');
            $table->time('closing_time')->comment('وقت انتهاء الدوام');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_clinics');
    }
};

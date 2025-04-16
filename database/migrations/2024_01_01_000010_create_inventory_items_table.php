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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dental_clinic_id')->constrained()->onDelete('cascade');
            $table->string('name'); // اسم المادة
            $table->integer('quantity')->default(0); // عدد المادة
            $table->enum('status', ['sufficient', 'damaged', 'low'])->default('sufficient'); // الحالة: كافي، تالف، نقص
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
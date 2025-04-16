<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient_xrays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('image_path');
            $table->enum('category', ['X', 'MRI', 'CT']);
            $table->text('notes')->nullable();
            $table->date('xray_date');
            $table->boolean('is_starred')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_xrays');
    }
};

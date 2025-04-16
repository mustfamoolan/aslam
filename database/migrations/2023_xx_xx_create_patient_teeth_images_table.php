<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient_teeth_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->enum('type', ['before', 'after']);
            $table->text('notes')->nullable();
            $table->date('image_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_teeth_images');
    }
};

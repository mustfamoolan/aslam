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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('session_type')->nullable()->after('amount')->comment('نوع الجلسة');
            $table->boolean('is_starred')->default(false)->after('status')->comment('مميز بنجمة');
            $table->boolean('is_archived')->default(false)->after('is_starred')->comment('مؤرشف');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['session_type', 'is_starred', 'is_archived']);
        });
    }
};

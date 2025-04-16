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
        Schema::table('patients', function (Blueprint $table) {
            // إضافة حقل is_starred إذا لم يكن موجودًا بالفعل
            if (!Schema::hasColumn('patients', 'is_starred')) {
                $table->boolean('is_starred')->default(false)->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // حذف الحقل إذا كان موجودًا
            if (Schema::hasColumn('patients', 'is_starred')) {
                $table->dropColumn('is_starred');
            }
        });
    }
};
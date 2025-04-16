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
            // إضافة حقل is_archived إذا لم يكن موجودًا بالفعل
            if (!Schema::hasColumn('patients', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('is_starred');
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
            if (Schema::hasColumn('patients', 'is_archived')) {
                $table->dropColumn('is_archived');
            }
        });
    }
};
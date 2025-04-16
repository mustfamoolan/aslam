<?php

namespace Database\Seeders;

use App\Models\DentalClinic;
use App\Models\InvoiceType;
use Illuminate\Database\Seeder;

class InvoiceTypeSeeder extends Seeder
{
    /**
     * تشغيل عملية إضافة البيانات.
     */
    public function run(): void
    {
        // الحصول على العيادة الأولى
        $clinic = DentalClinic::first();

        if (!$clinic) {
            $this->command->error('لا توجد عيادة! يرجى إضافة عيادة أولاً.');
            return;
        }

        // قائمة أنواع الفواتير
        $invoiceTypes = [
            'كشف أولي',
            'حشوة أسنان',
            'علاج عصب',
            'تنظيف أسنان',
            'خلع سن',
            'تركيب تقويم',
            'متابعة تقويم',
            'تركيب طربوش',
            'تبييض أسنان',
            'زراعة أسنان',
        ];

        // إضافة أنواع الفواتير
        foreach ($invoiceTypes as $type) {
            InvoiceType::create([
                'dental_clinic_id' => $clinic->id,
                'name' => $type,
            ]);
        }

        $this->command->info('تم إضافة أنواع الفواتير بنجاح!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * تشغيل عملية إضافة البيانات.
     */
    public function run(): void
    {
        // التأكد من وجود مريضين على الأقل
        $patient1 = Patient::find(1);
        $patient2 = Patient::find(2);

        if (!$patient1 || !$patient2) {
            $this->command->error('يجب أن يكون هناك مريضين على الأقل (ID: 1, 2,3,4,5,6,7,8,9,10)');
            return;
        }

        // أنواع الجلسات المختلفة
        $sessionTypes = [
            'فحص أولي',
            'حشوة أسنان',
            'علاج عصب',
            'تنظيف أسنان',
            'خلع سن',
            'تركيب تقويم',
            'متابعة تقويم',
            'تركيب طربوش',
            'تبييض أسنان',
            'زراعة أسنان'
        ];

        // تاريخ اليوم
        $today = Carbon::today();

        // إنشاء 15 موعد للمريض الأول
        for ($i = 0; $i < 15; $i++) {
            // إنشاء وقت عشوائي بين 9 صباحاً و 5 مساءً
            $hour = rand(9, 17);
            $minute = rand(0, 3) * 15; // 0, 15, 30, 45
            $time = Carbon::today()->setHour($hour)->setMinute($minute)->setSecond(0);

            Appointment::create([
                'patient_id' => 1,
                'appointment_date' => $today,
                'appointment_time' => $time,
                'amount' => rand(20, 200),
                'session_type' => $sessionTypes[array_rand($sessionTypes)],
                'status' => ['pending', 'completed', 'cancelled'][rand(0, 2)],
                'is_starred' => (bool)rand(0, 1),
                'is_archived' => (bool)rand(0, 1),
            ]);
        }

        // إنشاء 15 موعد للمريض الثاني
        for ($i = 0; $i < 15; $i++) {
            // إنشاء وقت عشوائي بين 9 صباحاً و 5 مساءً
            $hour = rand(9, 17);
            $minute = rand(0, 3) * 15; // 0, 15, 30, 45
            $time = Carbon::today()->setHour($hour)->setMinute($minute)->setSecond(0);

            Appointment::create([
                'patient_id' => 2,
                'appointment_date' => $today,
                'appointment_time' => $time,
                'amount' => rand(20, 200),
                'session_type' => $sessionTypes[array_rand($sessionTypes)],
                'status' => ['pending', 'completed', 'cancelled'][rand(0, 2)],
                'is_starred' => (bool)rand(0, 1),
                'is_archived' => (bool)rand(0, 1),
            ]);
        }

        $this->command->info('تم إنشاء 30 موعد بنجاح (15 لكل مريض)');
    }
}

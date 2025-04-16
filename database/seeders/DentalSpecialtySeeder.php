<?php

namespace Database\Seeders;

use App\Models\DentalSpecialty;
use Illuminate\Database\Seeder;

class DentalSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            'طب الأسنان العام',
            'تقويم الأسنان',
            'جراحة الفم والوجه والفكين',
            'طب أسنان الأطفال',
            'علاج جذور الأسنان',
            'أمراض اللثة',
            'طب الأسنان التجميلي',
            'زراعة الأسنان',
        ];

        foreach ($specialties as $specialty) {
            DentalSpecialty::create(['name' => $specialty]);
        }
    }
}

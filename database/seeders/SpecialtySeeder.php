<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
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
            Specialty::create(['name' => $specialty]);
        }
    }
}

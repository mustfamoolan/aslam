<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إنشاء المجلدات اللازمة لتخزين الصور
        $teethImagesPath = storage_path('app/public/patient_images/teeth');
        if (!file_exists($teethImagesPath)) {
            mkdir($teethImagesPath, 0755, true);
        }

        // إنشاء مجلد صور الأشعة
        $xrayImagesPath = storage_path('app/public/patient_images/xrays');
        if (!file_exists($xrayImagesPath)) {
            mkdir($xrayImagesPath, 0755, true);
        }
    }
}

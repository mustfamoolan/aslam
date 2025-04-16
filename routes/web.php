<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicNoteController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AllergyController;
use App\Http\Controllers\PatientNoteController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SystemInfoController;
use App\Http\Controllers\StatisticsController;
use App\Models\Patient;
use App\Http\Controllers\PatientImagesController;

// الصفحة الرئيسية - تم تعديلها لتوجيه المستخدم إلى صفحة تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('login');
});

// مسارات المصادقة
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// مسارات التسجيل
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/clinic', [RegisterController::class, 'registerClinic'])->name('register.clinic.submit');
Route::get('/register/doctor', [RegisterController::class, 'showDoctorRegistrationForm'])->name('register.doctor');
Route::post('/register/doctor', [RegisterController::class, 'registerDoctor'])->name('register.doctor.submit');

// مسار لوحة التحكم (محمي)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/latest-patients', [DashboardController::class, 'getLatestPatients'])->name('dashboard.latest-patients');
    Route::get('/dashboard/today-appointments', [DashboardController::class, 'getTodayAppointments'])->name('dashboard.today-appointments');
    Route::get('/dashboard/available-times', [DashboardController::class, 'getAvailableTimes'])->name('dashboard.available-times');
    Route::get('/doctor/profile', [DashboardController::class, 'editProfile'])->name('doctor.profile');
    Route::put('/doctor/update-profile', [DashboardController::class, 'updateDoctorProfile'])->name('doctor.update-profile');
    Route::put('/doctor/update-clinic', [DashboardController::class, 'updateClinicProfile'])->name('doctor.update-clinic');
    Route::get('/clinic-hours', [DashboardController::class, 'getClinicHours'])->name('get-clinic-hours');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{item}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::get('/inventory/categories', [InventoryController::class, 'categories'])->name('inventory.categories');
    Route::get('/inventory/suppliers', [InventoryController::class, 'suppliers'])->name('inventory.suppliers');
    Route::get('/inventory/reports', [InventoryController::class, 'reports'])->name('inventory.reports');
    Route::get('/system-info', [SystemInfoController::class, 'index'])->name('system.info');
});

// مسارات المرضى
Route::resource('patients', PatientController::class);
Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
Route::post('/patients/add-from-dashboard', [PatientController::class, 'addFromDashboard'])->name('patients.add-from-dashboard');
Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
Route::get('/patients/{patient}/images', [PatientController::class, 'images'])->name('patients.images');
Route::get('/patients/{patient}/appointments', [PatientController::class, 'appointments'])->name('patients.appointments');
Route::get('/patients/{patient}/invoices', [PatientController::class, 'invoices'])->name('patients.invoices');
Route::post('/allergies', [AllergyController::class, 'store'])->name('allergies.store');
Route::put('/allergies', [AllergyController::class, 'update'])->name('allergies.update');
Route::delete('/allergies', [AllergyController::class, 'destroy'])->name('allergies.destroy');
Route::get('/patients/search-json', [PatientController::class, 'searchJson'])->name('patients.search-json');

// مسارات المواعيد
Route::resource('appointments', AppointmentController::class);
Route::post('/appointments/{appointment}/toggle-star', [AppointmentController::class, 'toggleStar'])->name('appointments.toggle-star');
Route::post('/appointments/{appointment}/toggle-archive', [AppointmentController::class, 'toggleArchive'])->name('appointments.toggle-archive');
Route::post('/appointments/star-multiple', [AppointmentController::class, 'starMultiple'])->name('appointments.star-multiple');
Route::post('/appointments/archive-multiple', [AppointmentController::class, 'archiveMultiple'])->name('appointments.archive-multiple');
Route::get('/appointments/available-times', [AppointmentController::class, 'getAvailableTimes'])->name('appointments.available-times');
Route::get('appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');

// مسارات ملاحظات العيادة
Route::resource('notes', ClinicNoteController::class);
Route::post('/notes/{note}/toggle-importance', [ClinicNoteController::class, 'toggleImportance'])->name('notes.toggle-importance');
Route::post('/notes/quick-add', [ClinicNoteController::class, 'addQuickNote'])->name('notes.quick-add');

// مسارات الفواتير
Route::resource('invoices', InvoiceController::class);

// مسارات أنواع الفواتير
Route::post('/invoice-types', [InvoiceTypeController::class, 'store'])->name('invoice-types.store');
Route::put('/invoice-types/{invoiceType}', [InvoiceTypeController::class, 'update'])->name('invoice-types.update');
Route::delete('/invoice-types/{invoiceType}', [InvoiceTypeController::class, 'destroy'])->name('invoice-types.destroy');

// مسارات التنبيهات
Route::resource('notifications', NotificationController::class)->except(['create', 'edit', 'update']);
Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
Route::get('/notifications/latest', [NotificationController::class, 'getLatestNotifications'])->name('notifications.latest');

// مسارات ملاحظات المريض
Route::post('/patient-notes', [PatientNoteController::class, 'store'])->name('patient-notes.store');
Route::put('/patient-notes/{note}', [PatientNoteController::class, 'update'])->name('patient-notes.update');
Route::delete('/patient-notes/{note}', [PatientNoteController::class, 'destroy'])->name('patient-notes.destroy');
Route::post('/patient-notes/{note}/toggle-importance', [PatientNoteController::class, 'toggleImportance'])->name('patient-notes.toggle-importance');

// مسارات السجلات الطبية
Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
Route::get('/medical-records/sort', [MedicalRecordController::class, 'sort'])->name('medical_records.sort');
Route::get('/medical-records/search', [MedicalRecordController::class, 'search'])->name('medical_records.search');
Route::post('/medical-records/{id}/toggle-star', [MedicalRecordController::class, 'toggleStar'])->name('medical_records.toggle_star');
Route::post('/medical-records/{id}/toggle-archive', [MedicalRecordController::class, 'toggleArchive'])->name('medical_records.toggle_archive');

// طرق الإعدادات
Route::get('/settings', function () {
    return view('settings.index');
})->name('settings');

// طرق الإحصائيات
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/statistics/patients', [StatisticsController::class, 'getPatientStats'])->name('statistics.patients');
Route::get('/statistics/appointments', [StatisticsController::class, 'getAppointmentStats'])->name('statistics.appointments');
Route::get('/statistics/appointments/view', [StatisticsController::class, 'appointmentsView'])->name('statistics.appointments.view');
Route::get('/statistics/invoices', [StatisticsController::class, 'getInvoiceStats'])->name('statistics.invoices');
Route::get('/statistics/invoices/view', [StatisticsController::class, 'invoicesView'])->name('statistics.invoices.view');

// مسارات صور المرضى
Route::prefix('patients/{patient}')->name('patients.')->group(function () {
    // مسارات صور حالة الأسنان
    Route::post('/teeth-images', [PatientImagesController::class, 'storeTeethImage'])
        ->name('teeth-images.store');
    Route::delete('/teeth-images/{teethImage}', [PatientImagesController::class, 'destroyTeethImage'])
        ->name('teeth-images.destroy');

    // مسارات صور الأشعة
    Route::post('/xrays', [PatientImagesController::class, 'storeXray'])
        ->name('xrays.store');
    Route::patch('/xrays/{xrayId}/toggle-star', [PatientImagesController::class, 'toggleXrayStar'])
        ->name('patients.xrays.toggle-star');
    Route::delete('/xrays/{xray}', [PatientImagesController::class, 'destroyXray'])
        ->name('xrays.destroy');
});



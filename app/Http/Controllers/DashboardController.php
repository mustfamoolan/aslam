<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\DentalClinic;
use App\Models\ClinicNote;
use App\Models\InvoiceType;
use App\Models\Invoice;
use App\Models\DentalDoctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * إنشاء مثيل جديد من المتحكم
     */
    public function __construct()
    {
        // جلب معلومات الطبيب المسجل دخوله وإرسالها إلى جميع الصفحات
        if (Auth::check()) {
            $doctor = Auth::user();
            if ($doctor && $doctor->clinic) {
                $clinic = $doctor->clinic;

                View::share('currentDoctor', $doctor);
                View::share('currentClinic', $clinic);
            }
        }
    }

    /**
     * عرض لوحة التحكم
     */
    public function index(Request $request)
    {
        // إذا كان الطلب يريد الإحصائيات فقط
        if ($request->has('get_stats')) {
            return $this->getStats();
        }

        // حساب عدد المرضى الجدد خلال الشهر الحالي
        $newPatientsCount = Patient::whereMonth('registration_date', Carbon::now()->month)
            ->whereYear('registration_date', Carbon::now()->year)
            ->count();

        // حساب عدد مواعيد اليوم
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();

        // حساب عدد مواعيد الأمس للمقارنة
        $yesterdayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::yesterday())->count();

        // الحصول على مواعيد اليوم مع بيانات المرضى (أقصى 14 موعد)
        $todayAppointments = Appointment::with('patient')
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time')
            ->take(11)
            ->get();

        // إضافة قائمة المرضى للمودال
        $patients = Patient::all();

        // جلب آخر 4 مرضى تمت إضافتهم
        $latestPatients = Patient::latest()->take(4)->get();

        // جلب العيادة الحالية
        $clinic = DentalClinic::first();

        // جلب آخر 6 ملاحظات
        $latestNotes = ClinicNote::where('dental_clinic_id', $clinic->id)
            ->latest()
            ->take(6)
            ->get();

        // جلب أنواع الفواتير
        $invoiceTypes = InvoiceType::where('dental_clinic_id', $clinic->id)->get();

        // إضافة إحصائيات الفواتير
        $invoicesCount = Invoice::count();
        $totalAmount = Invoice::sum('amount');
        $paidAmount = Invoice::sum('paid_amount');
        $remainingAmount = Invoice::sum('remaining_amount');

        return view('dashboard', compact(
            'newPatientsCount',
            'todayAppointmentsCount',
            'yesterdayAppointmentsCount',
            'todayAppointments',
            'patients',
            'latestPatients',
            'clinic',
            'latestNotes',
            'invoiceTypes',
            'invoicesCount',
            'totalAmount',
            'paidAmount',
            'remainingAmount'
        ));
    }

    /**
     * جلب آخر المرضى المضافين للعرض في لوحة التحكم
     */
    public function getLatestPatients(Request $request)
    {
        $limit = $request->input('limit', 4);
        $latestPatients = Patient::latest()->take($limit)->get();

        return view('partials.latest_patients', compact('latestPatients'))->render();
    }

    /**
     * جلب مواعيد اليوم للعرض في لوحة التحكم
     */
    public function getTodayAppointments(Request $request)
    {
        $limit = $request->input('limit', null);

        $query = Appointment::with('patient')
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time');

        if ($limit) {
            $query->take($limit);
        }

        $todayAppointments = $query->get();

        return view('partials.today_appointments', compact('todayAppointments'))->render();
    }

    /**
     * الحصول على الأوقات المتاحة للتاريخ المحدد
     */
    public function getAvailableTimes(Request $request)
    {
        $date = $request->input('date');
        $clinicId = $request->input('clinic_id', 1); // استخدام معرف العيادة المرسل أو 1 كقيمة افتراضية

        if (!$date) {
            return response()->json(['error' => 'التاريخ مطلوب'], 400);
        }

        // الحصول على معلومات العيادة وأوقات الدوام
        $clinic = \App\Models\DentalClinic::findOrFail($clinicId);
        $openingTime = Carbon::parse($clinic->opening_time);
        $closingTime = Carbon::parse($clinic->closing_time);

        // الحصول على جميع المواعيد في هذا التاريخ
        $bookedAppointments = Appointment::whereDate('appointment_date', $date)
            ->pluck('appointment_time')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // إنشاء قائمة بجميع الأوقات المتاحة (من وقت بدء الدوام إلى وقت انتهاء الدوام بفاصل 30 دقيقة)
        $allTimes = [];
        $startTime = Carbon::parse($openingTime);
        $endTime = Carbon::parse($closingTime)->subMinutes(30); // نقص 30 دقيقة لأن آخر موعد يجب أن يبدأ قبل وقت الإغلاق بـ 30 دقيقة على الأقل

        while ($startTime <= $endTime) {
            $timeString = $startTime->format('H:i');
            $allTimes[] = [
                'time' => $timeString,
                'formatted' => $startTime->format('h:i A'),
                'available' => !in_array($timeString, $bookedAppointments)
            ];

            $startTime->addMinutes(30);
        }

        return response()->json($allTimes);
    }

    /**
     * الحصول على إحصائيات لوحة التحكم
     */
    public function getStats()
    {
        // حساب عدد مواعيد اليوم
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();

        // حساب عدد مواعيد الأمس للمقارنة
        $yesterdayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::yesterday())->count();

        // حساب عدد المرضى الجدد خلال الشهر الحالي
        $newPatientsCount = Patient::whereMonth('registration_date', Carbon::now()->month)
            ->whereYear('registration_date', Carbon::now()->year)
            ->count();

        // إضافة إحصائيات الفواتير
        $invoicesCount = Invoice::count();
        $totalAmount = Invoice::sum('amount');
        $paidAmount = Invoice::sum('paid_amount');
        $remainingAmount = Invoice::sum('remaining_amount');

        return response()->json([
            'todayAppointmentsCount' => $todayAppointmentsCount,
            'yesterdayAppointmentsCount' => $yesterdayAppointmentsCount,
            'newPatientsCount' => $newPatientsCount,
            'invoicesCount' => $invoicesCount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount
        ]);
    }

    /**
     * تحديث معلومات الطبيب
     */
    public function updateDoctorProfile(Request $request)
    {
        $doctor = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:dental_doctors,email,' . $doctor->id,
            'phone' => 'required|string|max:20|unique:dental_doctors,phone,' . $doctor->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $doctor->name = $request->name;
        $doctor->email = $request->email;
        $doctor->phone = $request->phone;

        if ($request->filled('password')) {
            $doctor->password = bcrypt($request->password);
        }

        $doctor->save();

        return redirect()->back()->with('success', 'تم تحديث معلومات الطبيب بنجاح');
    }

    /**
     * عرض صفحة تعديل معلومات الطبيب والعيادة
     */
    public function editProfile()
    {
        $doctor = Auth::user();
        $clinic = $doctor->clinic;

        return view('doctor.profile', compact('doctor', 'clinic'));
    }

    /**
     * تحديث معلومات العيادة
     */
    public function updateClinicProfile(Request $request)
    {
        $doctor = Auth::user();
        $clinic = $doctor->clinic;

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        $clinic->name = $request->name;
        $clinic->address = $request->address;
        $clinic->opening_time = $request->opening_time;
        $clinic->closing_time = $request->closing_time;

        $clinic->save();

        return redirect()->back()->with('success', 'تم تحديث معلومات العيادة بنجاح');
    }

    public function getClinicHours()
    {
        try {
            $clinicId = auth()->user()->dental_clinic_id;
            $clinic = \App\Models\DentalClinic::findOrFail($clinicId);

            // التأكد من التنسيق الصحيح
            $openingTime = $clinic->opening_time ? $clinic->opening_time->format('H:i') : '09:00';
            $closingTime = $clinic->closing_time ? $clinic->closing_time->format('H:i') : '17:30';

            // تسجيل القيم للتصحيح
            \Log::info('ساعات عمل العيادة:', [
                'clinic_id' => $clinicId,
                'opening_time' => $openingTime,
                'closing_time' => $closingTime
            ]);

            return response()->json([
                'opening_time' => $openingTime,
                'closing_time' => $closingTime
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ
            \Log::error('خطأ في الحصول على ساعات العيادة: ' . $e->getMessage());

            // إرجاع قيم افتراضية في حالة حدوث خطأ
            return response()->json([
                'opening_time' => '09:00',
                'closing_time' => '17:30',
                'error' => 'حدث خطأ أثناء جلب ساعات العيادة. تم استخدام القيم الافتراضية.'
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * عرض صفحة الإحصائيات الرئيسية
     */
    public function index()
    {
        return view('statistics.index');
    }

    /**
     * الحصول على إحصائيات المرضى
     */
    public function getPatientStats(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        // إجمالي عدد المرضى
        $totalPatients = Patient::count();

        // عدد المرضى الجدد في السنة المحددة
        $newPatients = Patient::whereYear('registration_date', $year)->count();

        // إحصائيات المرضى حسب الشهر للسنة المحددة
        $monthlyStats = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = Patient::whereYear('registration_date', $year)
                ->whereMonth('registration_date', $month)
                ->count();

            $monthlyStats[] = $count;
        }

        // إحصائيات المرضى حسب الجنس
        $genderStats = [
            'male' => Patient::where('gender', 'male')->count(),
            'female' => Patient::where('gender', 'female')->count()
        ];

        // إحصائيات المرضى حسب الفئة العمرية
        $ageStats = [
            'children' => Patient::where('age', '<', 18)->count(),
            'adults' => Patient::whereBetween('age', [18, 60])->count(),
            'seniors' => Patient::where('age', '>', 60)->count()
        ];

        return response()->json([
            'totalPatients' => $totalPatients,
            'newPatients' => $newPatients,
            'monthlyStats' => $monthlyStats,
            'genderStats' => $genderStats,
            'ageStats' => $ageStats,
            'year' => $year
        ]);
    }

    /**
     * الحصول على إحصائيات المواعيد
     */
    public function getAppointmentStats(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // إجمالي عدد المواعيد
        $totalAppointments = Appointment::count();

        // عدد المواعيد في السنة والشهر المحددين
        $filteredAppointments = Appointment::whereYear('appointment_date', $year);

        // إذا تم تحديد شهر، قم بتصفية البيانات حسب الشهر
        if ($month) {
            $filteredAppointments->whereMonth('appointment_date', $month);
        }

        $filteredAppointmentsCount = $filteredAppointments->count();

        // إحصائيات المواعيد حسب الشهر للسنة المحددة
        $monthlyStats = [];

        for ($m = 1; $m <= 12; $m++) {
            $count = Appointment::whereYear('appointment_date', $year)
                ->whereMonth('appointment_date', $m)
                ->count();

            $monthlyStats[] = $count;
        }

        // إحصائيات المواعيد حسب الحالة للفترة المحددة
        $statusQuery = Appointment::whereYear('appointment_date', $year);

        // إذا تم تحديد شهر، قم بتصفية البيانات حسب الشهر
        if ($month) {
            $statusQuery->whereMonth('appointment_date', $month);
        }

        $statusStats = [
            'completed' => (clone $statusQuery)->where('status', 'completed')->count(),
            'pending' => (clone $statusQuery)->where('status', 'pending')->count(),
            'cancelled' => (clone $statusQuery)->where('status', 'cancelled')->count()
        ];

        // متوسط عدد المواعيد اليومية في الفترة المحددة
        $avgQuery = Appointment::whereYear('appointment_date', $year);

        // إذا تم تحديد شهر، قم بتصفية البيانات حسب الشهر
        if ($month) {
            $avgQuery->whereMonth('appointment_date', $month);
        }

        $avgDailyAppointments = $avgQuery
            ->select(DB::raw('appointment_date, COUNT(*) as count'))
            ->groupBy('appointment_date')
            ->get()
            ->avg('count');

        return response()->json([
            'totalAppointments' => $totalAppointments,
            'filteredAppointmentsCount' => $filteredAppointmentsCount,
            'monthlyStats' => $monthlyStats,
            'statusStats' => $statusStats,
            'avgDailyAppointments' => round($avgDailyAppointments, 1),
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * الحصول على إحصائيات الفواتير
     */
    public function getInvoiceStats(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // إجمالي عدد الفواتير
        $totalInvoices = Invoice::count();

        // تصفية الفواتير حسب السنة والشهر
        $filteredInvoices = Invoice::whereYear('issue_date', $year);

        // إذا تم تحديد شهر، قم بتصفية البيانات حسب الشهر
        if ($month) {
            $filteredInvoices->whereMonth('issue_date', $month);
        }

        // إجمالي المبالغ للفترة المحددة
        $totalAmount = $filteredInvoices->sum('amount');
        $paidAmount = $filteredInvoices->sum('paid_amount');
        $remainingAmount = $filteredInvoices->sum('remaining_amount');

        // إحصائيات الفواتير حسب الشهر للسنة المحددة
        $monthlyStats = [];

        for ($m = 1; $m <= 12; $m++) {
            $amount = Invoice::whereYear('issue_date', $year)
                ->whereMonth('issue_date', $m)
                ->sum('amount');

            $monthlyStats[] = $amount;
        }

        // متوسط قيمة الفاتورة للفترة المحددة
        $avgInvoiceAmount = $filteredInvoices->avg('amount') ?: 0;

        return response()->json([
            'totalInvoices' => $totalInvoices,
            'filteredInvoicesCount' => $filteredInvoices->count(),
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
            'monthlyStats' => $monthlyStats,
            'avgInvoiceAmount' => round($avgInvoiceAmount, 2),
            'year' => $year,
            'month' => $month
        ]);
    }

    /**
     * عرض صفحة إحصائيات الفواتير
     */
    public function invoicesView()
    {
        return view('statistics.invoices');
    }

    /**
     * عرض صفحة إحصائيات الفواتير
     * عرض صفحة إحصائيات المواعيد
     */
    public function appointmentsView()
    {
        return view('statistics.appointments');
    }
}

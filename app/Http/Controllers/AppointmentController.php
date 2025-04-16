<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * عرض قائمة المواعيد
     */
    public function index()
    {
        $appointments = Appointment::with('patient')->latest()->paginate(10);
        $patients = Patient::all();
        return view('appointments.index', compact('appointments', 'patients'));
    }

    /**
     * تخزين موعد جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'session_type' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'session_title' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'appointment' => $appointment]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'تم إضافة الموعد بنجاح');
    }

    /**
     * عرض موعد محدد
     */
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    /**
     * عرض بيانات الموعد للتعديل (استجابة AJAX)
     */
    public function edit(Appointment $appointment)
    {
        // تحميل علاقة المريض
        $appointment->load('patient');

        // إرجاع البيانات بتنسيق JSON
        return response()->json([
            'appointment' => $appointment,
            'success' => true
        ]);
    }

    /**
     * تحديث موعد محدد
     */
    public function update(Request $request, Appointment $appointment)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'amount' => 'nullable|numeric|min:0',
            'session_type' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
            'note' => 'nullable|string',
            'session_title' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث الموعد
        $appointment->update([
            'patient_id' => $request->patient_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'amount' => $request->amount,
            'session_type' => $request->session_type,
            'status' => $request->status,
            'note' => $request->note,
            'session_title' => $request->session_title,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الموعد بنجاح'
            ]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    /**
     * حذف موعد محدد
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الموعد بنجاح'
            ]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }

    /**
     * تبديل حالة النجمة للموعد
     */
    public function toggleStar(Appointment $appointment)
    {
        // تبديل قيمة is_starred
        $appointment->is_starred = !$appointment->is_starred;
        $appointment->save();

        // للطلبات AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_starred' => $appointment->is_starred,
                'message' => $appointment->is_starred ? 'تم تمييز الموعد بنجاح' : 'تم إلغاء تمييز الموعد'
            ]);
        }

        // للطلبات العادية
        return redirect()->back()->with('success', $appointment->is_starred ? 'تم تمييز الموعد بنجاح' : 'تم إلغاء تمييز الموعد');
    }

    /**
     * تبديل حالة الأرشفة للموعد
     */
    public function toggleArchive(Appointment $appointment)
    {
        $appointment->is_archived = !$appointment->is_archived;
        $appointment->save();

        return response()->json([
            'success' => true,
            'is_archived' => $appointment->is_archived
        ]);
    }

    /**
     * تمييز مواعيد متعددة بنجمة
     */
    public function starMultiple(Request $request)
    {
        $appointmentIds = $request->appointment_ids;

        Appointment::whereIn('id', $appointmentIds)
            ->update(['is_starred' => true]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * أرشفة مواعيد متعددة
     */
    public function archiveMultiple(Request $request)
    {
        $appointmentIds = $request->appointment_ids;

        Appointment::whereIn('id', $appointmentIds)
            ->update(['is_archived' => true]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * الحصول على الأوقات المتاحة في تاريخ محدد
     */
    public function getAvailableTimes(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'date' => 'required|date',
            'clinic_id' => 'required|exists:dental_clinics,id',
        ]);

        $date = $request->date;
        $clinicId = $request->clinic_id;

        // الحصول على ساعات عمل العيادة
        $workingHours = [
            'start' => '09:00', // وقت بدء العمل
            'end' => '22:00',   // وقت انتهاء العمل
            'interval' => 30,   // فترة كل موعد بالدقائق
        ];

        // الحصول على المواعيد الموجودة في هذا اليوم
        $existingAppointments = Appointment::where('appointment_date', $date)
            ->where('dental_clinic_id', $clinicId)
            ->pluck('appointment_time')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // إنشاء قائمة بجميع الأوقات المتاحة
        $availableTimes = [];
        $startTime = Carbon::parse($workingHours['start']);
        $endTime = Carbon::parse($workingHours['end']);

        while ($startTime < $endTime) {
            $timeString = $startTime->format('H:i');
            $formattedTime = $startTime->format('h:i A');

            $isAvailable = !in_array($timeString, $existingAppointments);

            $availableTimes[] = [
                'time' => $timeString,
                'formatted' => $formattedTime,
                'available' => $isAvailable
            ];

            $startTime->addMinutes($workingHours['interval']);
        }

        return response()->json($availableTimes);
    }
}

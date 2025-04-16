<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientNoteController extends Controller
{
    /**
     * إضافة ملاحظة جديدة للمريض
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_important' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // إنشاء ملاحظة جديدة
            $note = PatientNote::create([
                'patient_id' => $request->patient_id,
                'title' => $request->title,
                'content' => $request->content,
                'is_important' => $request->is_important ?? false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تمت إضافة الملاحظة بنجاح',
                'note' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إضافة الملاحظة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث ملاحظة موجودة
     */
    public function update(Request $request, PatientNote $note)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_important' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // تحديث الملاحظة
            $note->update([
                'title' => $request->title,
                'content' => $request->content,
                'is_important' => $request->is_important ?? $note->is_important,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الملاحظة بنجاح',
                'note' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث الملاحظة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف ملاحظة
     */
    public function destroy(PatientNote $note)
    {
        try {
            $note->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الملاحظة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الملاحظة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تبديل حالة الأهمية للملاحظة
     */
    public function toggleImportance(PatientNote $note)
    {
        try {
            $note->update([
                'is_important' => !$note->is_important
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة الأهمية بنجاح',
                'is_important' => $note->is_important
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث حالة الأهمية: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $patients = Patient::where('full_name', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'full_name', 'age', 'phone_number', 'gender']);

        return response()->json($patients);
    }

    public function getAppointmentDetails($id)
    {
        $patient = Patient::findOrFail($id);
        $appointment = $patient->appointments()->latest()->first();

        if (!$appointment) {
            return response()->json([
                'patient_name' => $patient->full_name,
                'patient_age' => $patient->age,
                'message' => 'لا توجد مواعيد لهذا المريض'
            ]);
        }

        return response()->json([
            'patient_name' => $patient->full_name,
            'patient_age' => $patient->age,
            'appointment_time' => $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') : '',
            'appointment_date' => $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y/m/d') : '',
            'total_amount' => $appointment->amount ?? 0,
            'payment_type' => $appointment->payment_type ?? 'نقدي',
            'status' => $appointment->status == 'completed' ? 'مكتمل' : ($appointment->status == 'cancelled' ? 'ملغي' : 'قيد الانتظار'),
            'note' => $appointment->note ?? '',
            'session_title' => $appointment->session_title ?? 'موعد مع الطبيب',
            'session_details' => $appointment->session_details ?? '',
            'prescribed_medicine' => $appointment->prescribed_medicine ?? '',
            'usage_instructions' => $appointment->usage_instructions ?? ''
        ]);
    }
}

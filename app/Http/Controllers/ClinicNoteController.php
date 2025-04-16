<?php

namespace App\Http\Controllers;

use App\Models\ClinicNote;
use App\Models\DentalClinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClinicNoteController extends Controller
{
    /**
     * عرض قائمة الملاحظات
     */
    public function index()
    {
        // الحصول على العيادة الحالية (يمكن تعديله حسب منطق التطبيق)
        $clinic = DentalClinic::first();

        // جلب ملاحظات العيادة
        $notes = $clinic->notes()->latest()->get();

        return view('notes.index', compact('notes', 'clinic'));
    }

    /**
     * إضافة ملاحظة جديدة
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'is_important' => 'boolean',
            'dental_clinic_id' => 'required|exists:dental_clinics,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // إنشاء ملاحظة جديدة
        $note = ClinicNote::create([
            'dental_clinic_id' => $request->dental_clinic_id,
            'content' => $request->content,
            'is_important' => $request->is_important ?? false,
        ]);

        // إذا كان الطلب من Ajax
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تمت إضافة الملاحظة بنجاح',
                'note' => $note
            ]);
        }

        // إذا كان الطلب عادي
        return redirect()->route('notes.index')
            ->with('success', 'تمت إضافة الملاحظة بنجاح');
    }

    /**
     * تحديث ملاحظة
     */
    public function update(Request $request, ClinicNote $note)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'is_important' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // تحديث الملاحظة
        $note->update([
            'content' => $request->content,
            'is_important' => $request->is_important ?? $note->is_important,
        ]);

        // إذا كان الطلب من Ajax
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الملاحظة بنجاح',
                'note' => $note
            ]);
        }

        // إذا كان الطلب عادي
        return redirect()->route('notes.index')
            ->with('success', 'تم تحديث الملاحظة بنجاح');
    }

    /**
     * حذف ملاحظة
     */
    public function destroy(ClinicNote $note)
    {
        $note->delete();

        // إذا كان الطلب من Ajax
        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الملاحظة بنجاح'
            ]);
        }

        // إذا كان الطلب عادي
        return redirect()->route('notes.index')
            ->with('success', 'تم حذف الملاحظة بنجاح');
    }

    /**
     * تبديل حالة الأهمية للملاحظة
     */
    public function toggleImportance(ClinicNote $note)
    {
        $note->update([
            'is_important' => !$note->is_important
        ]);

        return response()->json([
            'status' => 'success',
            'is_important' => $note->is_important
        ]);
    }

    /**
     * إضافة ملاحظة سريعة من لوحة التحكم
     */
    public function addQuickNote(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'dental_clinic_id' => 'required|exists:dental_clinics,id',
            'is_important' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // إنشاء ملاحظة جديدة
            $note = ClinicNote::create([
                'dental_clinic_id' => $request->dental_clinic_id,
                'content' => $request->content,
                'is_important' => $request->has('is_important') ? true : false,
            ]);

            // التحقق من نجاح العملية
            if (!$note) {
                throw new \Exception('فشل في إنشاء الملاحظة');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'تمت إضافة الملاحظة بنجاح',
                'note' => $note
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ
            \Log::error('خطأ في إضافة ملاحظة: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إضافة الملاحظة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

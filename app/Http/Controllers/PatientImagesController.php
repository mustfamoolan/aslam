<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientTeethImage;
use App\Models\PatientXray;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatientImagesController extends Controller
{
    /**
     * إضافة صورة جديدة لحالة الأسنان
     */
    public function storeTeethImage(Request $request, Patient $patient)
    {
        try {
            Log::info('بدء عملية رفع صورة حالة الأسنان', [
                'patient_id' => $patient->id,
                'request_data' => $request->except(['image']),
                'has_image' => $request->hasFile('image'),
                'content_type' => $request->hasFile('image') ? $request->file('image')->getMimeType() : null,
                'file_size' => $request->hasFile('image') ? $request->file('image')->getSize() : null
            ]);

            // التحقق من وجود الصورة
            if (!$request->hasFile('image')) {
                Log::error('لم يتم العثور على ملف الصورة في الطلب');
                return response()->json([
                    'status' => 'error',
                    'message' => 'لم يتم العثور على ملف الصورة'
                ], 400);
            }

            // التحقق من صحة البيانات
            $validator = \Validator::make($request->all(), [
                'image' => 'required|image|max:5120', // 5MB كحد أقصى
                'type' => 'required|in:before,after',
                'notes' => 'nullable|string',
                'image_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                Log::error('فشل التحقق من البيانات', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'بيانات غير صالحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('تم التحقق من البيانات بنجاح');

            // التأكد من وجود المجلد
            $teethImagesPath = storage_path('app/public/patient_images/teeth');
            if (!file_exists($teethImagesPath)) {
                if (!mkdir($teethImagesPath, 0755, true)) {
                    Log::error('فشل في إنشاء مجلد تخزين الصور', ['path' => $teethImagesPath]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'فشل في إنشاء مجلد تخزين الصور'
                    ], 500);
                }
                Log::info('تم إنشاء مجلد تخزين الصور', ['path' => $teethImagesPath]);
            }

            // حفظ الصورة
            try {
                $path = $request->file('image')->store('patient_images/teeth', 'public');
                Log::info('تم حفظ الصورة بنجاح', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('فشل في حفظ الصورة', [
                    'error' => $e->getMessage(),
                    'file_size' => $request->file('image')->getSize(),
                    'file_extension' => $request->file('image')->extension()
                ]);
                throw $e;
            }

            // إنشاء سجل جديد
            $teethImage = new PatientTeethImage([
                'image_path' => $path,
                'type' => $request->type,
                'notes' => $request->notes,
                'image_date' => $request->image_date,
            ]);

            $patient->teethImages()->save($teethImage);
            Log::info('تم حفظ بيانات الصورة في قاعدة البيانات', ['image_id' => $teethImage->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة الصورة بنجاح',
                'image' => $teethImage
            ]);
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء رفع صورة حالة الأسنان', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء رفع الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة حالة الأسنان
     */
    public function destroyTeethImage(PatientTeethImage $teethImage)
    {
        try {
            Log::info('بدء عملية حذف صورة حالة الأسنان', ['image_id' => $teethImage->id]);

            // حذف الصورة من التخزين
            if (Storage::disk('public')->exists($teethImage->image_path)) {
                Storage::disk('public')->delete($teethImage->image_path);
                Log::info('تم حذف الصورة من التخزين');
            }

            // حذف السجل
            $teethImage->delete();
            Log::info('تم حذف بيانات الصورة من قاعدة البيانات');

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الصورة بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء حذف صورة حالة الأسنان', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة صورة أشعة جديدة
     */
    public function storeXray(Request $request, Patient $patient)
    {
        try {
            Log::info('بدء عملية رفع صورة أشعة', [
                'patient_id' => $patient->id,
                'request_data' => $request->except(['image']),
                'has_image' => $request->hasFile('image'),
                'content_type' => $request->hasFile('image') ? $request->file('image')->getMimeType() : null,
                'file_size' => $request->hasFile('image') ? $request->file('image')->getSize() : null
            ]);

            // التحقق من وجود الصورة
            if (!$request->hasFile('image')) {
                Log::error('لم يتم العثور على ملف الصورة في الطلب');
                return response()->json([
                    'status' => 'error',
                    'message' => 'لم يتم العثور على ملف الصورة'
                ], 400);
            }

            // التحقق من صحة البيانات
            $validator = \Validator::make($request->all(), [
                'image' => 'required|image|max:5120', // 5MB كحد أقصى
                'title' => 'required|string|max:255',
                'category' => 'required|in:X,MRI,CT',
                'notes' => 'nullable|string',
                'image_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                Log::error('فشل التحقق من بيانات الأشعة', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'بيانات غير صالحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            Log::info('تم التحقق من بيانات الأشعة بنجاح');

            // التأكد من وجود المجلد
            $xrayImagesPath = storage_path('app/public/patient_images/xrays');
            if (!file_exists($xrayImagesPath)) {
                mkdir($xrayImagesPath, 0755, true);
                Log::info('تم إنشاء مجلد تخزين صور الأشعة', ['path' => $xrayImagesPath]);
            }

            // حفظ الصورة
            $path = $request->file('image')->store('patient_images/xrays', 'public');
            Log::info('تم حفظ صورة الأشعة بنجاح', ['path' => $path]);

            // إنشاء سجل جديد
            $xray = new PatientXray([
                'title' => $request->title,
                'image_path' => $path,
                'category' => $request->category,
                'notes' => $request->notes,
                'xray_date' => $request->image_date,
                'is_starred' => false
            ]);

            $patient->xrays()->save($xray);
            Log::info('تم حفظ بيانات صورة الأشعة في قاعدة البيانات', ['xray_id' => $xray->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة صورة الأشعة بنجاح',
                'xray' => $xray
            ]);
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء رفع صورة الأشعة', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء رفع صورة الأشعة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تبديل حالة تمييز صورة الأشعة بنجمة
     */
    public function toggleXrayStar(Patient $patient, $xrayId)
    {
        try {
            Log::info('بدء عملية تبديل حالة التمييز بنجمة', [
                'patient_id' => $patient->id,
                'xray_id' => $xrayId
            ]);

            // البحث عن صورة الأشعة
            $xray = PatientXray::findOrFail($xrayId);

            // التحقق من أن الأشعة تنتمي للمريض المحدد
            if ($xray->patient_id != $patient->id) {
                Log::error('محاولة الوصول إلى أشعة لا تنتمي للمريض', [
                    'xray_id' => $xrayId,
                    'patient_id' => $patient->id,
                    'xray_patient_id' => $xray->patient_id
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'غير مصرح بالوصول إلى هذه الأشعة'
                ], 403);
            }

            // تبديل الحالة
            $xray->is_starred = !$xray->is_starred;
            $xray->save();

            Log::info('تم تبديل حالة التمييز بنجمة بنجاح', [
                'xray_id' => $xray->id,
                'is_starred' => $xray->is_starred
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة التمييز بنجاح',
                'is_starred' => $xray->is_starred
            ]);
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء تبديل حالة التمييز بنجمة', [
                'error' => $e->getMessage(),
                'xray_id' => $xrayId,
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث حالة التمييز: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف صورة أشعة
     */
    public function destroyXray(PatientXray $xray)
    {
        try {
            Log::info('بدء عملية حذف صورة أشعة', ['xray_id' => $xray->id]);

            // حذف الصورة من التخزين
            if (Storage::disk('public')->exists($xray->image_path)) {
                Storage::disk('public')->delete($xray->image_path);
                Log::info('تم حذف صورة الأشعة من التخزين');
            }

            // حذف السجل
            $xray->delete();
            Log::info('تم حذف بيانات صورة الأشعة من قاعدة البيانات');

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف صورة الأشعة بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء حذف صورة الأشعة', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف صورة الأشعة: ' . $e->getMessage()
            ], 500);
        }
    }
}

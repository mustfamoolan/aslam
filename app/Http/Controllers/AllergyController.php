<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllergyController extends Controller
{
    /**
     * إضافة حساسية جديدة للمريض
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'name' => 'required|string|max:255',
            'severity' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // البحث عن المريض
            $patient = Patient::findOrFail($request->patient_id);

            // البحث عن الحساسية أو إنشاء واحدة جديدة
            $allergy = Allergy::firstOrCreate(['name' => $request->name]);

            // التحقق مما إذا كان المريض لديه بالفعل هذه الحساسية
            if ($patient->allergies()->where('allergy_id', $allergy->id)->exists()) {
                // تحديث البيانات الإضافية
                $patient->allergies()->updateExistingPivot($allergy->id, [
                    'severity' => $request->severity,
                    'notes' => $request->notes,
                ]);
            } else {
                // إضافة الحساسية للمريض مع البيانات الإضافية
                $patient->allergies()->attach($allergy->id, [
                    'severity' => $request->severity,
                    'notes' => $request->notes,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تمت إضافة الحساسية بنجاح',
                'allergy' => $allergy
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إضافة الحساسية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث حساسية موجودة للمريض
     */
    public function update(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'allergy_id' => 'required|exists:allergies,id',
            'name' => 'required|string|max:255',
            'severity' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // البحث عن المريض
            $patient = Patient::findOrFail($request->patient_id);

            // البحث عن الحساسية
            $allergy = Allergy::findOrFail($request->allergy_id);

            // تحديث اسم الحساسية إذا تغير
            if ($allergy->name !== $request->name) {
                // التحقق مما إذا كانت الحساسية الجديدة موجودة بالفعل
                $existingAllergy = Allergy::where('name', $request->name)->first();

                if ($existingAllergy) {
                    // إذا كانت موجودة، فقم بفصل الحساسية القديمة وربط الجديدة
                    $patient->allergies()->detach($allergy->id);
                    $allergy = $existingAllergy;
                } else {
                    // إذا لم تكن موجودة، فقم بتحديث الاسم
                    $allergy->name = $request->name;
                    $allergy->save();
                }
            }

            // تحديث البيانات الإضافية في جدول العلاقة
            $patient->allergies()->syncWithoutDetaching([
                $allergy->id => [
                    'severity' => $request->severity,
                    'notes' => $request->notes,
                ]
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الحساسية بنجاح',
                'allergy' => $allergy
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث الحساسية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف حساسية من المريض
     */
    public function destroy(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'allergy_id' => 'required|exists:allergies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // البحث عن المريض
            $patient = Patient::findOrFail($request->patient_id);

            // فصل الحساسية عن المريض
            $patient->allergies()->detach($request->allergy_id);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الحساسية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الحساسية: ' . $e->getMessage()
            ], 500);
        }
    }
}

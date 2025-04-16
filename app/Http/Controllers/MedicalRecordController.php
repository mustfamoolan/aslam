<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class MedicalRecordController extends Controller
{
    /**
     * عرض صفحة السجلات الطبية
     */
    public function index()
    {
        // جلب جميع المرضى مرتبين حسب تاريخ التسجيل (الأحدث أولاً)
        $patients = Patient::latest('registration_date')->get();

        return view('medical_records.index', compact('patients'));
    }

    /**
     * ترتيب المرضى حسب المعيار المحدد
     */
    public function sort(Request $request)
    {
        $sortType = $request->input('sort', 'latest');

        // ترتيب المرضى حسب المعيار المحدد
        switch ($sortType) {
            case 'name':
                $patients = Patient::orderBy('full_name')->get();
                break;
            case 'latest':
                $patients = Patient::latest('registration_date')->get();
                break;
            case 'archived':
                // افتراضياً، يمكن إضافة حقل is_archived للمرضى
                $patients = Patient::where('is_archived', true)->get();
                break;
            case 'starred':
                // افتراضياً، يمكن إضافة حقل is_starred للمرضى
                $patients = Patient::where('is_starred', true)->get();
                break;
            case 'diagnosis':
                // يمكن ترتيب المرضى حسب التشخيص إذا كان هناك علاقة بين المرضى والتشخيصات
                $patients = Patient::latest('registration_date')->get();
                break;
            default:
                $patients = Patient::latest('registration_date')->get();
                break;
        }

        // إرجاع جزء من العرض يحتوي على جدول المرضى فقط
        return view('medical_records.partials.patients_table', compact('patients'))->render();
    }

    /**
     * البحث عن المرضى
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // البحث في أسماء المرضى وأرقامهم وأرقام هواتفهم
        $patients = Patient::where('full_name', 'like', "%{$query}%")
            ->orWhere('patient_number', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->get();

        // إرجاع جزء من العرض يحتوي على جدول المرضى فقط
        return view('medical_records.partials.patients_table', compact('patients'))->render();
    }

    /**
     * تبديل حالة النجمة للمريض
     */
    public function toggleStar(Request $request, $id)
    {
        try {
            $patient = Patient::findOrFail($id);

            // التحقق من وجود الحقل is_starred
            if (Schema::hasColumn('patients', 'is_starred')) {
                $patient->is_starred = !$patient->is_starred;
                $patient->save();

                return response()->json([
                    'success' => true,
                    'is_starred' => $patient->is_starred,
                    'message' => $patient->is_starred ? 'تمت إضافة المريض إلى المفضلة' : 'تمت إزالة المريض من المفضلة'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'حقل is_starred غير موجود في جدول المرضى'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تبديل حالة النجمة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تبديل حالة الأرشفة للمريض
     */
    public function toggleArchive(Request $request, $id)
    {
        try {
            $patient = Patient::findOrFail($id);

            // التحقق من وجود الحقل is_archived
            if (Schema::hasColumn('patients', 'is_archived')) {
                $patient->is_archived = !$patient->is_archived;
                $patient->save();

                return response()->json([
                    'success' => true,
                    'is_archived' => $patient->is_archived,
                    'message' => $patient->is_archived ? 'تمت أرشفة المريض بنجاح' : 'تم إلغاء أرشفة المريض بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'حقل is_archived غير موجود في جدول المرضى'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تبديل حالة الأرشفة: ' . $e->getMessage()
            ], 500);
        }
    }
}

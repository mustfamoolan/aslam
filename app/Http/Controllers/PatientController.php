<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use App\Models\ChronicDisease;
use App\Models\DentalClinic;
use App\Models\Patient;
use App\Models\PatientNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * عرض قائمة المرضى
     */
    public function index()
    {
        $patients = Patient::with(['chronicDiseases', 'allergies'])->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    /**
     * عرض نموذج إضافة مريض جديد
     */
    public function create()
    {
        $chronicDiseases = ChronicDisease::all();
        $allergies = Allergy::all();

        // الحصول على آخر رقم تسلسلي للمريض
        $lastPatientNumber = Patient::max('patient_number') ?? 0;
        $nextPatientNumber = $lastPatientNumber + 1;

        return view('patients.create', compact('chronicDiseases', 'allergies', 'nextPatientNumber'));
    }

    /**
     * تخزين مريض جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'allergies' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // الحصول على آخر رقم تسلسلي للمريض
        $lastPatientNumber = Patient::max('patient_number') ?? 0;
        $nextPatientNumber = $lastPatientNumber + 1;

        // الحصول على معرف العيادة (يمكن تعديله حسب طريقة تخزين معرف العيادة في التطبيق)
        $dentalClinicId = 1; // افتراضي، يمكن تغييره حسب منطق التطبيق

        try {
            DB::beginTransaction();

            // إنشاء المريض الجديد
            $patient = Patient::create([
                'dental_clinic_id' => $dentalClinicId,
                'full_name' => $request->full_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'patient_number' => $nextPatientNumber,
                'registration_date' => Carbon::now()->toDateString(),
                'registration_time' => Carbon::now()->toTimeString(),
                'notes' => $request->notes,
            ]);

            // إضافة الأمراض المزمنة إذا وجدت
            if ($request->has('chronic_diseases')) {
                $chronicDiseases = $request->chronic_diseases;
                foreach ($chronicDiseases as $disease) {
                    // التحقق مما إذا كان المرض موجوداً بالفعل أو إنشاء مرض جديد
                    $chronicDisease = ChronicDisease::firstOrCreate(['name' => $disease]);
                    $patient->chronicDiseases()->attach($chronicDisease->id);
                }
            }

            // إضافة الحساسية إذا وجدت
            if ($request->has('allergies')) {
                $allergiesList = $request->allergies;
                foreach ($allergiesList as $allergyName) {
                    // التحقق مما إذا كانت الحساسية موجودة بالفعل أو إنشاء حساسية جديدة
                    $allergy = Allergy::firstOrCreate(['name' => $allergyName]);
                    $patient->allergies()->attach($allergy->id);
                }
            }

            DB::commit();

            return redirect()->route('patients.index')
                ->with('success', 'تم إضافة المريض بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المريض: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض بيانات مريض محدد
     */
    public function show(Patient $patient)
    {
        $patient->load(['chronicDiseases', 'allergies', 'patientNotes']);
        return view('patients.show', compact('patient'));
    }

    /**
     * عرض نموذج تعديل بيانات مريض
     */
    public function edit(Patient $patient)
    {
        $patient->load(['chronicDiseases', 'allergies']);
        $chronicDiseases = ChronicDisease::all();
        $allergies = Allergy::all();

        return view('patients.edit', compact('patient', 'chronicDiseases', 'allergies'));
    }

    /**
     * تحديث بيانات مريض في قاعدة البيانات
     */
    public function update(Request $request, Patient $patient)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'chronic_diseases' => 'nullable|array',
            'allergies' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // تحديث بيانات المريض
            $patient->update([
                'full_name' => $request->full_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            // تحديث الأمراض المزمنة
            $patient->chronicDiseases()->detach(); // حذف العلاقات القديمة
            if ($request->has('chronic_diseases')) {
                $chronicDiseases = $request->chronic_diseases;
                foreach ($chronicDiseases as $disease) {
                    $chronicDisease = ChronicDisease::firstOrCreate(['name' => $disease]);
                    $patient->chronicDiseases()->attach($chronicDisease->id);
                }
            }

            // تحديث الحساسية
            $patient->allergies()->detach(); // حذف العلاقات القديمة
            if ($request->has('allergies')) {
                $allergiesList = $request->allergies;
                foreach ($allergiesList as $allergyName) {
                    $allergy = Allergy::firstOrCreate(['name' => $allergyName]);
                    $patient->allergies()->attach($allergy->id);
                }
            }

            DB::commit();

            return redirect()->route('patients.index')
                ->with('success', 'تم تحديث بيانات المريض بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المريض: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف مريض من قاعدة البيانات
     */
    public function destroy(Patient $patient)
    {
        try {
            // حذف العلاقات أولاً
            $patient->chronicDiseases()->detach();
            $patient->allergies()->detach();

            // ثم حذف المريض
            $patient->delete();

            return redirect()->route('patients.index')
                ->with('success', 'تم حذف المريض بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')
                ->with('error', 'حدث خطأ أثناء حذف المريض: ' . $e->getMessage());
        }
    }

    /**
     * البحث عن مريض
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $patients = Patient::where('full_name', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->orWhere('patient_number', 'like', "%{$query}%")
            ->with(['chronicDiseases', 'allergies'])
            ->paginate(10);

        return view('patients.index', compact('patients', 'query'));
    }

    /**
     * البحث عن مريض وإرجاع النتائج بتنسيق JSON
     */
    public function searchJson(Request $request)
    {
        $query = $request->input('query');

        $patients = Patient::where('full_name', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->orWhere('patient_number', 'like', "%{$query}%")
            ->select('id', 'full_name', 'age', 'gender', 'phone_number')
            ->take(10)
            ->get();

        return response()->json($patients);
    }

    /**
     * ربط المودال بإضافة مريض جديد من الصفحة الرئيسية
     */
    public function addFromDashboard(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'allergies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // الحصول على آخر رقم تسلسلي للمريض
        $lastPatientNumber = Patient::max('patient_number') ?? 0;
        $nextPatientNumber = $lastPatientNumber + 1;

        // الحصول على معرف العيادة
        $dentalClinicId = 1; // افتراضي، يمكن تغييره حسب منطق التطبيق

        try {
            DB::beginTransaction();

            // إنشاء المريض الجديد
            $patient = Patient::create([
                'dental_clinic_id' => $dentalClinicId,
                'full_name' => $request->full_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'patient_number' => $nextPatientNumber,
                'registration_date' => Carbon::now()->toDateString(),
                'registration_time' => Carbon::now()->toTimeString(),
                'notes' => $request->notes,
            ]);

            // معالجة الأمراض المزمنة (تأتي كنص مفصول بفواصل)
            if ($request->has('chronic_diseases') && !empty($request->chronic_diseases)) {
                $chronicDiseaseNames = explode(',', $request->chronic_diseases);
                foreach ($chronicDiseaseNames as $diseaseName) {
                    $diseaseName = trim($diseaseName);
                    if (!empty($diseaseName)) {
                        $chronicDisease = ChronicDisease::firstOrCreate(['name' => $diseaseName]);
                        $patient->chronicDiseases()->attach($chronicDisease->id);
                    }
                }
            }

            // معالجة الحساسية (تأتي كنص مفصول بفواصل)
            if ($request->has('allergies') && !empty($request->allergies)) {
                $allergyNames = explode(',', $request->allergies);
                foreach ($allergyNames as $allergyName) {
                    $allergyName = trim($allergyName);
                    if (!empty($allergyName)) {
                        $allergy = Allergy::firstOrCreate(['name' => $allergyName]);
                        $patient->allergies()->attach($allergy->id);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'تم إضافة المريض بنجاح',
                'patient' => $patient
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إضافة المريض: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض صور وأشعة المريض
     */
    public function images(Patient $patient)
    {
        // تحميل صور حالة الأسنان والأشعة
        $patient->load(['teethImages', 'xrays']);

        // فصل صور حالة الأسنان إلى قبل وبعد
        $beforeImages = $patient->teethImages->where('type', 'before');
        $afterImages = $patient->teethImages->where('type', 'after');

        // تصنيف الأشعة
        $xrays = $patient->xrays;

        return view('patients.images', compact('patient', 'beforeImages', 'afterImages', 'xrays'));
    }

    /**
     * عرض صفحة مواعيد المريض
     */
    public function appointments(Patient $patient)
    {
        // تحميل مواعيد المريض مع معلومات المريض
        $patient->load('appointments');

        // الحصول على المواعيد مرتبة حسب التاريخ (الأحدث أولاً)
        $appointments = $patient->appointments->sortByDesc(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time;
        });

        return view('patients.appointments', compact('patient', 'appointments'));
    }

    /**
     * عرض صفحة فواتير المريض
     */
    public function invoices(Patient $patient)
    {
        // تحميل فواتير المريض
        $patient->load('invoices');

        // حساب الإحصائيات
        $totalInvoices = $patient->invoices->count();
        $totalAmount = $patient->invoices->sum('amount');
        $paidAmount = $patient->invoices->sum('paid_amount');
        $remainingAmount = $totalAmount - $paidAmount;

        return view('patients.invoices', compact('patient', 'totalInvoices', 'totalAmount', 'paidAmount', 'remainingAmount'));
    }
}

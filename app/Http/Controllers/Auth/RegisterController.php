<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DentalClinic;
use App\Models\DentalDoctor;
use App\Models\DentalSpecialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * عرض صفحة تسجيل العيادة (الخطوة الأولى)
     */
    public function showRegistrationForm()
    {
        return view('auth.register-clinic');
    }

    /**
     * معالجة بيانات العيادة والانتقال إلى الخطوة التالية
     */
    public function registerClinic(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        // تخزين بيانات العيادة في الجلسة
        Session::put('clinic_data', $request->all());

        // الانتقال إلى صفحة تسجيل الطبيب
        return redirect()->route('register.doctor');
    }

    /**
     * عرض صفحة تسجيل الطبيب (الخطوة الثانية)
     */
    public function showDoctorRegistrationForm()
    {
        // التأكد من وجود بيانات العيادة في الجلسة
        if (!Session::has('clinic_data')) {
            return redirect()->route('register');
        }

        $specialties = DentalSpecialty::all();
        return view('auth.register-doctor', compact('specialties'));
    }

    /**
     * معالجة بيانات الطبيب وإنشاء الحساب
     */
    public function registerDoctor(Request $request)
    {
        // التحقق من البيانات مع رسائل خطأ مخصصة
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_id' => 'required|exists:dental_specialties,id',
            'phone' => [
                'required',
                'string',
                'unique:dental_doctors,phone',
            ],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل. هذا الرقم لديه حساب مسجل مسبقاً.',
        ]);

        // استرجاع بيانات العيادة من الجلسة
        $clinicData = Session::get('clinic_data');

        if (!$clinicData) {
            return redirect()->route('register')->withErrors(['error' => 'بيانات العيادة غير موجودة']);
        }

        // إنشاء العيادة
        $clinic = DentalClinic::create([
            'name' => $clinicData['name'],
            'address' => $clinicData['address'],
            'opening_time' => $clinicData['opening_time'],
            'closing_time' => $clinicData['closing_time'],
        ]);

        // إنشاء الطبيب
        $doctor = DentalDoctor::create([
            'name' => $request->name,
            'dental_specialty_id' => $request->specialty_id,
            'dental_clinic_id' => $clinic->id,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // حذف بيانات العيادة من الجلسة
        Session::forget('clinic_data');

        // تسجيل الدخول تلقائياً
        auth()->login($doctor);

        return redirect()->route('dashboard');
    }
}

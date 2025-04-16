<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DentalDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // البحث عن الطبيب باستخدام رقم الهاتف
        $doctor = DentalDoctor::where('phone', $request->phone)->first();

        // التحقق من وجود الطبيب وصحة كلمة المرور
        if ($doctor && Hash::check($request->password, $doctor->password)) {
            // تسجيل الدخول
            Auth::login($doctor);

            // إعادة التوجيه إلى لوحة التحكم
            return redirect()->intended('/dashboard');
        }

        // إذا فشل تسجيل الدخول
        return back()->withErrors([
            'phone' => 'بيانات الاعتماد المقدمة غير صحيحة.',
        ])->withInput($request->only('phone'));
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

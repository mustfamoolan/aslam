<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - نظام إدارة عيادات الأسنان</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background-image: url('/images/dental-background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 3rem 5%;
        }

        .register-form {
            max-width: 630px;
            width: 100%;
            padding: 3rem;
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.36);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
            margin: 2rem 0;
        }

        .register-title {
            color: #2c7b90;
            font-size: 3rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .register-subtitle {
            color: #2c7b90;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-label {
            color: #2c7b90;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .form-control::placeholder, .form-select::placeholder {
            color: #adb5bd;
        }

        .btn-register {
            background-color: #2c7b90;
            border: none;
            padding: 1rem;
            font-size: 1.5rem;
            border-radius: 12px;
            width: 100%;
            margin-top: 2rem;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background-color: #236478;
            transform: translateY(-3px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
            font-size: 1.2rem;
        }

        .login-link a {
            color: #2c7b90;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        @media (max-width: 992px) {
            .register-form {
                max-width: 90%;
                padding: 2.5rem;
            }

            .register-title {
                font-size: 2.5rem;
            }

            .register-subtitle {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            body {
                justify-content: center;
                padding: 2rem 1rem;
            }

            .register-form {
                max-width: 100%;
                margin: 1rem 0;
                padding: 2rem 1.5rem;
            }

            .register-title {
                font-size: 2.2rem;
            }

            .register-subtitle {
                font-size: 1.3rem;
                margin-bottom: 1.5rem;
            }

            .form-label {
                font-size: 1.3rem;
            }

            .form-control, .form-select, .btn-register, .login-link {
                font-size: 1.1rem;
            }

            .mb-4 {
                margin-bottom: 1.2rem !important;
            }
        }

        @media (max-height: 800px) {
            body {
                padding: 1rem;
            }

            .register-form {
                padding: 2rem;
                margin: 1rem 0;
            }

            .register-title {
                font-size: 2.2rem;
                margin-bottom: 0.5rem;
            }

            .register-subtitle {
                font-size: 1.3rem;
                margin-bottom: 1.5rem;
            }

            .mb-4 {
                margin-bottom: 1rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="register-form">
        <h1 class="register-title">إنشاء حساب</h1>
        <h2 class="register-subtitle">معلومات الطبيب</h2>

        <form method="POST" action="{{ route('register.doctor.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}"
                       placeholder="اكتب اسمك" required autofocus>
                @error('name')
                    <div class="invalid-feedback" style="font-size: 1rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="specialty_id" class="form-label">الاختصاص</label>
                <select class="form-select @error('specialty_id') is-invalid @enderror"
                        id="specialty_id" name="specialty_id" required>
                    <option value="" selected disabled>اختر اختصاصك</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
                @error('specialty_id')
                    <div class="invalid-feedback" style="font-size: 1rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                       id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="000 0000 0000" required>
                @error('phone')
                    <div class="invalid-feedback" style="font-size: 1rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">الرمز</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="اكتب رمز حسابك" required>
                @error('password')
                    <div class="invalid-feedback" style="font-size: 1rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">تأكيد الرمز</label>
                <input type="password" class="form-control"
                       id="password_confirmation" name="password_confirmation"
                       placeholder="أعد كتابة رمز حسابك" required>
            </div>

            <button type="submit" class="btn btn-primary btn-register">إنشاء حساب</button>
        </form>

        <div class="login-link">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

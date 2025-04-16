<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول - نظام إدارة عيادات الأسنان</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-image: url('/images/dental-background.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-right: 5%;
            padding-left: 10%;
        }

        .login-form {
            max-width: 630px;
            width: 100%;
            padding: 6rem 4rem;
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.36);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
        }

        .login-title {
            color: #2c7b90;
            font-size: 4rem;
            margin-bottom: 4.5rem;
            text-align: center;
        }

        .form-label {
            color: #2c7b90;
            font-weight: bold;
            font-size: 1.8rem;
        }

        .form-control {
            padding: 1.2rem 1.5rem;
            border-radius: 12px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .btn-login {
            background-color: #2c7b90;
            border: none;
            padding: 1.2rem;
            font-size: 1.8rem;
            border-radius: 12px;
            width: 100%;
            margin-top: 3rem;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #236478;
            transform: translateY(-3px);
        }

        .register-link {
            text-align: center;
            margin-top: 3.5rem;
            color: #6c757d;
            font-size: 1.5rem;
        }

        .register-link a {
            color: #2c7b90;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .mb-4 {
            margin-bottom: 3rem !important;
        }

        @media (max-width: 992px) {
            .login-form {
                max-width: 490px;
                padding: 4.5rem 3rem;
            }

            .login-title {
                font-size: 3.5rem;
                margin-bottom: 3.5rem;
            }

            .mb-4 {
                margin-bottom: 2.5rem !important;
            }
        }

        @media (max-width: 768px) {
            body {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .login-form {
                max-width: 63%;
                margin: 0 1rem;
                padding: 3rem 2rem;
            }

            .login-title {
                font-size: 3rem;
                margin-bottom: 3rem;
            }

            .form-label {
                font-size: 1.5rem;
            }

            .form-control, .btn-login, .register-link {
                font-size: 1.3rem;
            }

            .mb-4 {
                margin-bottom: 2rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h1 class="login-title">تسجيل دخول</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                       id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="أدخل رقم الهاتف" required autofocus>
                @error('phone')
                    <div class="invalid-feedback" style="font-size: 1.2rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">الرمز</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="اكتب رمز حسابك" required>
                @error('password')
                    <div class="invalid-feedback" style="font-size: 1.2rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-login">تسجيل دخول</button>
        </form>

        <div class="register-link">
            ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ واحداً</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'إعدادات النظام')</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px; /* مساحة للفوتر */
            margin: 0;
        }

        /* تنسيق السلايدر الجانبي */
        .sidebar {
            width: 208px;
            height: 1280px; /* طول السلايدر */
            background-color: #22577A; /* لون مختلف عن السجل الطبي */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            right: 10px;
            top: 10px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 1000;
        }

        /* تنسيق عنوان السلايدر */
        .sidebar-header {
            width: 100%;
            padding: 20px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .sidebar-header i {
            font-size: 18px;
            margin-left: 8px;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
        }

        /* تنسيق زر العودة للرئيسية */
        .home-button {
            margin-top: auto;
            margin-bottom: 16px;
            background-color: #34939C;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: 80%;
            font-size: 14px;
        }

        .home-button i {
            margin-left: 8px;
        }

        .home-button:hover {
            background-color: #34939C;
            color: white;
        }

        /* تنسيق المحتوى الرئيسي */
        .main-content {
            margin-right: 230px; /* مساحة للسلايدر */
            padding: 20px;
        }

        /* تنسيق للشاشات الصغيرة */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .main-content {
                margin-right: 80px;
            }

            .sidebar-title {
                display: none;
            }

            .home-button {
                width: 40px;
                padding: 8px;
            }

            .home-button span {
                display: none;
            }
        }

        /* تنسيق كارد الإعدادات */
        .settings-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .settings-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .settings-card-title {
            color: #22577A;
            font-size: 24px;
            font-weight: bold;
        }

        /* تنسيق عناصر الإعدادات */
        .settings-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .settings-item:last-child {
            border-bottom: none;
        }

        .settings-label {
            display: flex;
            align-items: center;
        }

        .settings-icon {
            width: 40px;
            height: 40px;
            background-color: #e9f7f7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            color: #5ECBC7;
            font-size: 18px;
        }

        .settings-text {
            font-size: 16px;
            color: #333;
        }

        .settings-description {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }

        /* تنسيق المفاتيح والأزرار */
        .form-switch {
            padding-right: 0;
        }

        .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-right: 0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #5ECBC7;
            border-color: #5ECBC7;
        }

        .time-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .time-btn {
            background-color: #f0f0f0;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            color: #333;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .time-btn.active {
            background-color: #5ECBC7;
            color: white;
        }

        .theme-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .theme-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s;
        }

        .theme-btn.light {
            background-color: #f8f9fa;
            border-color: #ddd;
        }

        .theme-btn.dark {
            background-color: #343a40;
        }

        .theme-btn.active {
            border-color: #5ECBC7;
            transform: scale(1.1);
        }

        .language-selector {
            position: relative;
        }

        .language-btn {
            display: flex;
            align-items: center;
            background-color: #f0f0f0;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            color: #333;
            font-size: 14px;
            cursor: pointer;
        }

        .language-btn i {
            margin-right: 10px;
        }

        /* تنسيق الفوتر */
        .footer {
            background-color: transparent;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-right: 230px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
        }

        .footer-links a {
            color: #5ECBC7;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #22577A;
            text-decoration: underline;
        }

        .footer-id {
            color: #777;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 5px 10px;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            .footer {
                margin-right: 80px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- السلايدر الجانبي -->
    <div class="sidebar">
        <!-- عنوان السلايدر -->
        <div class="sidebar-header">
            <i class="fas fa-cog"></i>
            <span class="sidebar-title">الإعــــدادات</span>
        </div>

        <!-- زر العودة للرئيسية -->
        <a href="{{ route('dashboard') }}" class="home-button">
            <i class="fas fa-home"></i>
            <span>الصفحة الرئيسية</span>
        </a>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- فوتر الصفحة -->
    <div class="footer">
        <div class="footer-links">
            <a href="#">شروط الاستخدام</a>
            <a href="#">سياسة الخصوصية</a>
        </div>
        <div class="footer-id">
            ID : {{ rand(1000000000, 9999999999) }}
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>

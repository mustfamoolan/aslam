<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'معلومات النظام')</title>
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

        /* تنسيق كارد المعلومات */
        .info-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .info-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .info-card-title {
            color: #22577A;
            font-size: 24px;
            font-weight: bold;
        }

        .info-item {
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
        }

        .info-label {
            color: #5ECBC7;
            font-weight: bold;
            margin-left: 10px;
        }

        .info-value {
            color: #333;
        }

        .company-logo {
            max-width: 100px;
            margin-bottom: 20px;
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
            <i class="fas fa-info-circle"></i>
            <span class="sidebar-title">معلومات النظام</span>
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

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'السجل الطبي')</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* تنسيق السلايدر الجانبي */
        .sidebar {
            width: 208px;
            height: 1280px; /* طول السلايدر */
            background-color: #1e5a7e;
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
            background-color: #3ca99e;
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
            background-color: #2d8a80;
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
    </style>
    @yield('styles')
</head>
<body>
    <!-- السلايدر الجانبي -->
    <div class="sidebar">
        <!-- عنوان السلايدر -->
        <div class="sidebar-header">
            <img src="{{ asset('icons/fi-sr-address-book.png') }}" alt="السجلات الطبية" style="width: 20px; height: 20px; margin-left: 8px;">
            <span class="sidebar-title">السجلات الطبية</span>
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'الإحصائيات')</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            height: 1280px;
            background-color: #22577A;
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

        /* تنسيق قائمة الأزرار */
        .stats-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 400px;
            margin-bottom: 100px;
        }

        /* إضافة تنسيق لأزرار القائمة في السلايدر الأيمن */
        .stats-menu-item {
            width: 80%;
            background-color: transparent;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            position: relative;
        }

        .stats-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            color: white;
        }

        .stats-menu-item.active {
            background-color: #34939C;
        }

        .stats-menu-item i {
            margin-left: 10px;
            font-size: 20px;
        }

        /* إضافة أيقونة دائرية للأزرار */
        .stats-menu-item .icon-circle {
            width: 40px;
            height: 40px;
            background-color: #E8F1F2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            margin-left: 0;
        }

        .stats-menu-item .icon-circle i {
            margin-left: 0;
            color: #22577A;
        }

        /* تنسيق النص في الأزرار */
        .stats-menu-item span {
            margin-right: 10px;
        }

        /* تنسيق زر العودة للرئيسية */
        .home-button {
            margin-top: auto;
            margin-bottom: 16px;
            background-color: #34939C;
            color: white;
            border: none;
            border-radius: 15px;
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
            padding-top: 50px; /* إضافة مساحة من الأعلى */
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

        /* تنسيق كارد الإحصائيات */
        .stats-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
            margin-top: 20px; /* إضافة هامش علوي */
            min-height: 1200px;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stats-title {
            color: #22577A;
            font-size: 24px;
            font-weight: bold;
        }

        .year-selector {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .year-selector i {
            margin-right: 10px;
        }

        /* تنسيق بطاقة الإحصائيات */
        .stats-summary {
            display: flex;
            margin-bottom: 30px;
        }

        .stats-box {
            background-color: #e9f7ff;
            border-radius: 15px;
            padding: 20px;
            flex: 1;
        }

        .stats-box-title {
            color: #22577A;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .stats-box-value {
            color: #22577A;
            font-size: 28px;
            font-weight: bold;
        }

        .stats-box-subtitle {
            color: #22577A;
            font-size: 18px;
            margin-top: 10px;
        }

        .stats-edit-btn {
            width: 50px;
            height: 120px;
            background-color: #22577A;
            border-radius: 15px;
            margin: 0 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .stats-range {
            background-color: white;
            border-radius: 15px;
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .range-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .range-label {
            color: #22577A;
            font-weight: bold;
        }

        .range-value {
            background-color: #e9f7ff;
            border-radius: 10px;
            padding: 5px 15px;
            min-width: 80px;
            text-align: center;
        }

        /* تنسيق الرسم البياني */
        .chart-container {
            position: relative;
            height: 800px; /* زيادة ارتفاع الرسم البياني */
            margin-top: 30px;
        }

        .chart-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .chart-label {
            display: flex;
            align-items: center;
        }

        .chart-label-color {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-left: 5px;
        }

        .chart-label-text {
            font-size: 14px;
            color: #666;
        }

        /* تنسيق السلايدر الجانبي للإحصائيات */
        .stats-sidebar {
            display: none; /* إخفاء السلايدر الإضافي */
        }

        /* إضافة تنسيق لأزرار القائمة في السلايدر الأيمن */
        .stats-menu-item {
            width: 80%;
            background-color: transparent;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s;
            position: relative;
        }

        .stats-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            color: white;
        }

        .stats-menu-item.active {
            background-color: #34939C;
        }

        .stats-menu-item i {
            margin-left: 10px;
            font-size: 20px;
        }

        /* إضافة أيقونة دائرية للأزرار */
        .stats-menu-item .icon-circle {
            width: 40px;
            height: 40px;
            background-color: #E8F1F2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            margin-left: 0;
        }

        .stats-menu-item .icon-circle i {
            margin-left: 0;
            color: #22577A;
        }

        /* إلغاء تنسيق الفوتر */
        .footer {
            display: none; /* إخفاء الفوتر */
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
            <i class="fas fa-chart-bar"></i>
            <span class="sidebar-title">الإحصائيات</span>
        </div>

        <!-- أزرار الإحصائيات -->
        <div class="stats-menu">
            <a href="{{ route('statistics') }}" class="stats-menu-item {{ request()->routeIs('statistics') && !request()->routeIs('*.view') ? 'active' : '' }}">
                <div class="icon-circle">
                    <i class="fas fa-user-injured"></i>
                </div>
                <span>المــرضــى</span>
            </a>

            <a href="{{ route('statistics.appointments.view') }}" class="stats-menu-item {{ request()->routeIs('statistics.appointments.view') ? 'active' : '' }}">
                <div class="icon-circle">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span>المــواعيــد</span>
            </a>

            <a href="{{ route('statistics.invoices.view') }}" class="stats-menu-item {{ request()->routeIs('statistics.invoices.view') ? 'active' : '' }}">
                <div class="icon-circle">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <span>الفــواتيــر</span>
            </a>
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

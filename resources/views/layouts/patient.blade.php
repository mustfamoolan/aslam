<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            display: flex;
            direction: rtl;
            background-color: #f5f5f5;
        }

        /*
        * لتغيير طول السلايدر، قم بتعديل خاصية height هنا
        * القيمة الحالية 90vh تعني 90% من ارتفاع الشاشة
        * يمكنك زيادتها إلى 95vh أو 100vh للحصول على سلايدر أطول
        * كما يمكنك تعديل خاصية top لتغيير موضع السلايدر من الأعلى
        */
        .sidebar {
            width: 208px;
            height: 1280px; /* قم بتعديل هذه القيمة لزيادة طول السلايدر */
            background-color: #1e5a7e;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            right: 10px;
            top: 10px; /* قم بتقليل هذه القيمة لرفع السلايدر للأعلى */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sidebar-header {
            text-align: center;
            padding: 15px 0;
            width: 100%;
            margin-bottom: 80px; /* إضافة هامش أسفل العنوان لإنزال الأفاتار */
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-title i {
            margin-left: 8px;
            font-size: 20px;
        }

        .patient-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #cfe2f3;
            margin: 20px auto;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.2);
            position: relative;
            top: 60px; /* إنزال الأفاتار للأسفل */
        }

        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .patient-name {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0 4px;
            margin-top: 70px; /* زيادة المسافة فوق الاسم */
        }

        .patient-age {
            font-size: 12px;

            color: white;

        }

        .sidebar-menu {
            width: 100%;
            margin-top: 20px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            position: relative;
            margin-bottom: 8px;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .menu-icon {
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 12px;
        }

        .menu-icon i {
            color: #1e5a7e;
            font-size: 16px;
        }

        .menu-text {
            font-size: 14px;
        }

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

        .content-area {
            flex: 1;
            margin-right: 228px;
            padding: 20px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <i class="fas fa-notes-medical"></i>
                السجل الطبي
            </div>

            <div class="patient-avatar">
                <img src="{{ asset($patient->gender == 'female' ? 'images/22.png' : 'images/11.png') }}" alt="{{ $patient->full_name }}">
            </div>

            <div class="patient-name">{{ $patient->full_name }}</div>
            <div class="patient-age">{{ $patient->age }} سنة</div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('patients.show', $patient) }}" class="menu-item {{ request()->routeIs('patients.show') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="menu-text">الملف الشخصي</div>
            </a>

            <a href="{{ route('patients.images', $patient) }}" class="menu-item {{ request()->routeIs('patients.images') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="menu-text">الصور والأشعة</div>
            </a>

            <a href="{{ route('patients.appointments', $patient) }}" class="menu-item {{ request()->routeIs('patients.appointments') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="menu-text">المواعيــــد</div>
            </a>

            <a href="{{ route('patients.invoices', $patient->id) }}" class="menu-item {{ request()->routeIs('patients.invoices') ? 'active' : '' }}">
                <div class="menu-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="menu-text">الفواتير</div>
            </a>
        </div>

        <a href="{{ route('dashboard') }}" class="home-button">
            <i class="fas fa-home"></i>
            الصفحة الرئيسية
        </a>
    </div>

    <div class="content-area">
        @yield('content')
    </div>

    @yield('scripts')

    <!-- يرجى التأكد من أن ملف التخطيط يحتوي على مكتبة Bootstrap و jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

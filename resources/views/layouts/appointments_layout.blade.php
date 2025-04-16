<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام إدارة عيادات الأسنان')</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #22577A;
            --secondary-color: #22577A;
            --light-bg: #f8f9fa;
            --sidebar-width: 118px;
            --sidebar-bg-top: #22577A;
            --sidebar-bg-bottom: #1d5f70;
            --sidebar-accent: #34939C;
        }

        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 264px;
            height: 1280px;
            background: linear-gradient(to bottom, var(--sidebar-bg-top), var(--sidebar-bg-bottom));
            position: fixed;
            right: 10px;
            top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            padding: 20px 0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar-header h5 {
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
        }

        .sidebar-menu {
            width: 100%;
        }

        .sidebar-footer {
            margin-top: auto;
            width: 100%;
            padding: 10px;
        }

        .sidebar-btn {
            width: 90%;
            background-color: var(--sidebar-accent);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin: 5px auto;
            display: block;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-btn:hover {
            background-color: #34939C;
            color: white;
        }

        .main-content {
            margin-right: 290px;
            width: calc(100% - 290px);
            padding: 20px;
            margin-top: 10px;
        }

        .add-appointment-btn {
            background-color: #34939C;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 500px auto;
            width: 90%;
            height: 71px;
            text-decoration: none;
        }

        .add-appointment-btn i {
            margin-left: 8px;
            font-size: 20px;
        }

        /* تعديل أيقونة الإضافة */
        .add-icon-circle {
            background-color: white;
            color: #34939C;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
        }

        .add-icon-circle i {
            margin: 0;
            font-size: 16px;
        }

        /* تعديلات لجعل الشريط الجانبي متجاوب */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
                height: 100vh;
                right: 0;
                top: 0;
                border-radius: 0;
            }

            .main-content {
                margin-right: 240px;
                width: calc(100% - 240px);
            }

            .add-appointment-btn {
                margin: 300px auto;
                height: 60px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
            }

            .main-content {
                margin-right: 200px;
                width: calc(100% - 200px);
            }

            .sidebar-header h5 {
                font-size: 20px;
            }

            .add-appointment-btn {
                margin: 200px auto;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-radius: 0;
                padding: 10px 0;
            }

            .main-content {
                margin-right: 0;
                width: 100%;
            }

            .sidebar-content {
                flex-direction: row;
            }

            .sidebar-menu, .sidebar-footer {
                width: 50%;
            }

            .add-appointment-btn {
                margin: 10px auto;
                height: 50px;
            }
        }

        /* تنسيق مربع البحث */
        .search-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .search-input-container {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .search-input {
            border: none;
            flex-grow: 1;
            padding: 8px;
            font-size: 14px;
            color: #555;
            outline: none;
        }

        .search-input::placeholder {
            color: #aaa;
        }

        .search-icon-right {
            color: #aaa;
            font-size: 18px;
            margin-left: 10px;
        }

        .search-icons-left {
            display: flex;
            align-items: center;
        }

        .search-icon-left {
            color: #aaa;
            font-size: 18px;
            margin-left: 15px;
        }

        .filter-icon {
            color: white;
            background-color: #34929B;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* تنسيقات النافذة المنبثقة */
        .rtl-modal .modal-header {
            flex-direction: row-reverse;
            border-bottom: 2px solid #1e5a7e;
            background-color: #f8f9fa;
        }

        .rtl-modal .modal-title {
            color: #1e5a7e;
            font-weight: bold;
        }

        .rtl-modal .modal-footer {
            justify-content: flex-start;
            border-top: 1px solid #eee;
        }

        .rtl-modal .form-label {
            color: #1e5a7e;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: right;
            display: block;
        }

        .rtl-modal .input-group-text {
            background-color: #1e5a7e;
            color: white;
            border: none;
        }

        .rtl-modal .btn-primary {
            background-color: #1e5a7e;
            border-color: #1e5a7e;
        }

        .rtl-modal .btn-primary:hover {
            background-color: #174a66;
            border-color: #174a66;
        }

        .rtl-modal .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .rtl-modal .close {
            margin: -1rem auto -1rem -1rem;
        }

        .rtl-modal .modal-body {
            padding: 20px;
        }

        .rtl-modal .form-control {
            text-align: right;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- الشريط الجانبي -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> المواعيد</h5>
        </div>

        <div class="sidebar-content">
            <div class="sidebar-menu">
                <a href="#" class="add-appointment-btn" id="addAppointmentButton">
                    <div class="add-icon-circle">
                        <i class="fas fa-plus"></i>
                    </div>
                    إضافة موعد جديد
                </a>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('dashboard') }}" class="sidebar-btn">
                    <i class="fas fa-home"></i> الصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <div class="search-container">
            <div class="search-input-container">
                <div class="search-icon-right">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" class="search-input" placeholder="ابحث عن موعد، مريض، تاريخ، جلسة...">
            </div>
            <div class="search-icons-left">
                <div class="filter-icon" id="filterButton">
                    <i class="fas fa-sliders-h"></i>
                </div>
            </div>
        </div>
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

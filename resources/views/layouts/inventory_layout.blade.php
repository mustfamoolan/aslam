<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'نظام المخزون')</title>
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

        /* تنسيق قائمة السلايدر */
        .sidebar-menu {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }

        .sidebar-menu-item {
            width: 85%;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu-item:hover, .sidebar-menu-item.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu-item i {
            font-size: 18px;
            margin-left: 10px;
            width: 24px;
            text-align: center;
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

            .sidebar-menu-item span {
                display: none;
            }
        }

        /* تنسيق البحث */
        .search-container {
            display: flex;
            justify-content: flex-start;
            width: 100%;
            margin-bottom: 25px;
            padding: 0 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 8px;
            padding: 0;
            width: 100%;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            overflow: hidden;
        }

        .search-bar button {
            background-color: #34929B;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .search-bar button:hover {
            background-color: #34929B;
        }

        .search-bar input {
            border: none;
            outline: none;
            flex: 1;
            padding: 15px 20px;
            font-family: 'Alexandria', sans-serif;
            font-size: 14px;
            color: #666;
            text-align: right;
        }

        .search-bar input::placeholder {
            color: #aaa;
        }

        /* تنسيق كارد الترتيب */
        .sorting-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin: 20px auto;
            width: 300px;
            text-align: center;
            position: absolute;
            left: 50px;
            top: 200px;
        }

        .sorting-card-title {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .sorting-card-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sort-card-btn {
            background-color: #f5f7fa;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            text-align: center;
        }

        .sort-card-btn:hover, .sort-card-btn.active {
            background-color: #e6f7f8;
            color: #2E8B57;
        }

        /* تنسيق زر إضافة عنصر */
        .add-item-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .add-item-btn {
            background-color: #007ED0D1;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(46, 139, 87, 0.2);
        }

        .add-item-btn i {
            margin-left: 8px;
            font-size: 18px;
        }

        .add-item-btn:hover {
            background-color: #007ED0D1;
            transform: translateY(-2px);
        }

        /* تنسيق جدول المخزون */
        .inventory-container {
            width: 70%;
            margin-right: 20px;
            margin-bottom: 20px;
            padding: 0 20px;
        }

        .inventory-wrapper {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .inventory-table-container {
            padding: 0;
            overflow-x: auto;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .inventory-table th {
            background-color: #f0f7fa;
            padding: 15px 10px;
            text-align: center;
            font-weight: bold;
            color: #2E8B57;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .inventory-table td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            font-size: 14px;
        }

        .inventory-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .inventory-table tr:hover {
            background-color: #f5f9fa;
        }

        .actions-cell {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background-color: #e0e0e0;
        }

        .edit-btn {
            color: #2E8B57;
        }

        .delete-btn {
            color: #e74c3c;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- السلايدر الجانبي -->
    <div class="sidebar">
        <!-- عنوان السلايدر -->
        <div class="sidebar-header">
            <i class="fas fa-boxes"></i>
            <span class="sidebar-title">نظام المخزون</span>
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
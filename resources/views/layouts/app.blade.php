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
            --sidebar-expanded-width: 300px;
            --sidebar-bg-top: #22577A;
            --sidebar-bg-bottom: #1d5f70;
            --sidebar-accent: #38A3A5;
        }

        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar-bg-top) 0%, var(--sidebar-bg-bottom) 100%);
            color: white;
            position: fixed;
            top: 10px;
            right: 10px;
            border-radius: 10px;
            height: 1280px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .sidebar.expanded {
            width: var(--sidebar-expanded-width);
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .sidebar-logo {
            width: 60px;
            height: 70px;
            margin-bottom: 80px;
        }

        .user-avatar-container {
            margin-bottom: 150px;
            transition: all 0.3s ease;
        }

        .sidebar.expanded .user-avatar-container {
            display: none;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .sidebar-accent {
            position: relative;
            background-color: transparent;
            width: 100%;
            padding-top: 150px;
            padding-right: 10px;
            padding-bottom: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: auto;
        }

        .sidebar-accent::before {
            content: '';
            position: absolute;
            top: -50px;
            right: 10px;
            width: 100%;
            height: 100px;
            background-color: var(--sidebar-accent);
            border-radius: 0 100% 0 0;
            z-index: -1;
        }

        .sidebar-accent-middle {
            position: absolute;
            top: 50px;
            right: 10px;
            width: 100%;
            height: calc(100% - 100px);
            background-color: var(--sidebar-accent);
            z-index: -2;
        }

        .sidebar-accent::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: 10px;
            width: 100%;
            height: 100px;
            background-color: var(--sidebar-accent);
            border-radius: 0 0 100% 0;
            z-index: -1;
        }

        .menu-item {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
            overflow: hidden;
        }

        .sidebar.expanded .menu-item {
            width: 90%;
            border-radius: 10px;
            justify-content: flex-start;
            padding-right: 15px;
        }

        .menu-text {
            display: none;
            margin-right: 15px;
            font-size: 16px;
            white-space: nowrap;
        }

        .sidebar.expanded .menu-text {
            display: block;
        }

        .menu-icon {
            font-size: 35px;
        }

        .sidebar-bottom {
            margin-top: auto;
            margin-bottom: 15px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bottom-icon {
            color: white;
            font-size: 20px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-decoration: none;
        }

        .bottom-icon:hover {
            transform: scale(1.1);
        }

        .bottom-text {
            display: none;
            margin-right: 15px;
            font-size: 16px;
            white-space: nowrap;
            color: white;
        }

        .sidebar.expanded .bottom-text {
            display: block;
        }

        .sidebar.expanded .bottom-icon {
            justify-content: flex-start;
            padding-right: 30px;
        }

        .main-content {
            flex: 1;
            margin-right: calc(var(--sidebar-width) + 20px);
            margin-left: 320px;
            padding: 20px;
            transition: all 0.3s;
        }

        .sidebar.expanded ~ .main-content {
            margin-right: calc(var(--sidebar-expanded-width) + 20px);
        }

        .side-slider {
            width: 300px;
            background-color: #22577A;
            color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            position: fixed;
            top: 10px;
            left: 10px;
            bottom: 10px;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease, width 0.3s ease;
            transform: translateX(0);
        }

        .side-slider.expanded {
            width: 400px;
        }

        .side-slider.collapsed {
            transform: translateX(-310px);
            width: 300px;
        }

        .side-slider-toggle {
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            width: 20px;
            height: 60px;
            background-color: #22577A;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
        }

        /* تنسيق الملف الشخصي في السلايدر الموسع */
        .expanded-profile {
            display: none;
            text-align: center;
            padding: 20px 0;
            width: 100%;
        }

        .sidebar.expanded .expanded-profile {
            display: block;
        }

        .profile-card {
            background-color: #38A3A5;
            border-radius: 10px;
            padding: 20px;
            margin: 0 15px;
            position: relative;
        }

        .expanded-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            margin: -60px auto 10px;
        }

        .clinic-name {
            font-size: 22px;
            font-weight: bold;
            color: white;
            margin-bottom: 5px;
        }

        .clinic-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 15px;
        }

        .edit-profile-btn {
            background-color: #22577A;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-profile-btn:hover {
            background-color: #1a4560;
        }

        /* تنسيق القائمة في السلايدر الموسع */
        .expanded-menu {
            display: none;
            padding: 20px 0;
        }

        .sidebar.expanded .expanded-menu {
            display: block;
        }

        .expanded-menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background-color 0.3s;
        }

        .expanded-menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .expanded-menu-item i {
            font-size: 20px;
            margin-left: 15px;
            width: 24px;
            text-align: center;
        }

        .expanded-menu-item span {
            font-size: 16px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .stats-sublabel {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .stats-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 2rem;
            opacity: 0.2;
        }

        .action-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-button i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #555;
        }

        .table td {
            vertical-align: middle;
        }

        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .action-icon {
            color: #777;
            margin: 0 5px;
            font-size: 1.1rem;
        }

        .action-icon:hover {
            color: var(--primary-color);
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #777;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 1200px) {
            .main-content {
                margin-left: 0;
            }

            .side-slider {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-right: 0;
                padding: 15px;
            }

            .sidebar {
                transform: translateX(60px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-toggle {
                display: block;
            }
        }

        .sidebar-close-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 10;
        }

        .sidebar-close-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar.expanded .sidebar-close-btn {
            display: flex;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <!-- Logo and Brand -->
            <div class="logo-container">
                <img src="{{ asset('images/dental-logo.png') }}" alt="Logo" class="sidebar-logo">
            </div>

            <!-- User Avatar Container -->
            <div class="user-avatar-container">
                <img src="{{ asset('images/11.png') }}" alt="User" class="user-avatar">
            </div>

            <!-- الملف الشخصي الموسع (يظهر فقط عند توسيع السلايدر) -->
            <div class="expanded-profile">
                <div class="profile-card">
                    <img src="{{ asset('images/11.png') }}" alt="User" class="expanded-avatar">
                    <div class="clinic-name">{{ $currentDoctor->name ?? 'الطبيب' }}</div>
                    <div class="clinic-subtitle">{{ $currentClinic->name ?? 'العيادة' }}</div>
                    <button class="edit-profile-btn" id="editProfileBtn">تعديل المعلومات</button>
                </div>
            </div>

            <!-- القائمة الموسعة (تظهر فقط عند توسيع السلايدر) -->
            <div class="expanded-menu">
                <!-- سيتم إزالة هذا القسم لأنه يحتوي على أيقونات مكررة -->
            </div>

            <!-- Accent Section with Menu Items -->
            <div class="sidebar-accent">
                <div class="sidebar-accent-middle"></div>

                <a href="{{ route('statistics') }}" class="menu-item">
                    <i class="fas fa-chart-bar menu-icon"></i>
                    <span class="menu-text">الإحصائيات</span>
                </a>
                <a href="{{ route('medical-records.index') }}" class="menu-item">
                    <img src="{{ asset('icons/fi-sr-address-bookb.png') }}" alt="السجلات" class="menu-icon">
                    <span class="menu-text">السجلات</span>
                </a>
                <a href="{{ route('appointments.index') }}" class="menu-item">
                    <i class="fas fa-calendar-alt menu-icon"></i>
                    <span class="menu-text">المواعيد</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="menu-item">
                    <i class="fas fa-briefcase-medical menu-icon"></i>
                    <span class="menu-text">المخزون</span>
                </a>
            </div>

            <!-- Bottom Icons -->
            <div class="sidebar-bottom">
                <a href="#" class="bottom-icon">
                    <i class="fas fa-moon"></i>
                    <span class="bottom-text">الوضع الداكن</span>
                </a>
                <a href="{{ route('settings') }}" class="bottom-icon">
                    <i class="fas fa-cog"></i>
                    <span class="bottom-text">الإعــــدادات</span>
                </a>
                <a href="{{ route('system.info') }}" class="bottom-icon">
                    <i class="fas fa-info-circle"></i>
                    <span class="bottom-text">معلومات النظام</span>
                </a>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <!-- زر إغلاق السايدبار الموسع -->
            <div class="sidebar-close-btn">
                <i class="fas fa-times"></i>
            </div>
        </div>

        <!-- Side Slider (فارغ) -->
        <div class="side-slider" id="sideSlider">
            <div class="side-slider-toggle" id="sideSliderToggle">
                <i class="fas fa-chevron-left"></i>
            </div>
            <!-- هنا يمكن إضافة محتوى السلايدر الجانبي حسب الحاجة -->
            @yield('side_slider')
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            const sideSlider = document.getElementById('sideSlider');
            const sideSliderToggle = document.getElementById('sideSliderToggle');
            const sidebarElement = document.getElementById('sidebar');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // تفعيل توسيع/تقليص السلايدر الجانبي
            sideSliderToggle.addEventListener('click', function() {
                sideSlider.classList.toggle('collapsed');
                // تغيير اتجاه أيقونة التوسيع/التقليص
                const icon = this.querySelector('i');
                if (sideSlider.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });

            // تفعيل زر إغلاق السايدبار
            document.querySelector('.sidebar-close-btn').addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث إلى السايدبار
                document.getElementById('sidebar').classList.remove('expanded');
            });

            // تعديل سلوك النقر على السايدبار
            sidebarElement.addEventListener('click', function(e) {
                // إذا كان السايدبار مغلقًا، افتحه
                if (!this.classList.contains('expanded')) {
                    this.classList.add('expanded');
                }
                // لا نقوم بإغلاق السايدبار عند النقر عليه
            });
        });

        // تحميل التنبيهات
        $(document).ready(function() {
            // تحميل عدد التنبيهات غير المقروءة
            function loadUnreadCount() {
                $.ajax({
                    url: "{{ route('notifications.unread-count') }}",
                    method: "GET",
                    success: function(response) {
                        const count = response.count;
                        const badge = $('#notification-badge');

                        if (count > 0) {
                            badge.text(count).show();
                        } else {
                            badge.hide();
                        }
                    }
                });
            }

            // تحميل آخر التنبيهات
            function loadLatestNotifications() {
                $.ajax({
                    url: "{{ route('notifications.latest') }}",
                    method: "GET",
                    success: function(response) {
                        const notifications = response.notifications;
                        const container = $('#notifications-container');

                        container.empty();

                        if (notifications.length > 0) {
                            notifications.forEach(notification => {
                                const isRead = notification.is_read ? 'read' : 'unread';
                                const typeClass = notification.type === 'info' ? 'info' : (notification.type === 'warning' ? 'warning' : 'danger');

                                container.append(`
                                    <a href="#" class="dropdown-item notification-item ${isRead}" data-id="${notification.id}">
                                        <div class="notification-icon ${typeClass}">
                                            <i class="fas fa-${notification.type === 'info' ? 'info-circle' : (notification.type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle')}"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-title">${notification.title}</div>
                                            <div class="notification-text">${notification.content}</div>
                                            <div class="notification-time">${moment(notification.created_at).fromNow()}</div>
                                        </div>
                                    </a>
                                `);
                            });
                        } else {
                            container.append(`
                                <div class="text-center p-3">
                                    <p class="mb-0">لا توجد تنبيهات</p>
                                </div>
                            `);
                        }

                        // إضافة مستمع الأحداث للتنبيهات
                        $('.notification-item').on('click', function(e) {
                            e.preventDefault();

                            const id = $(this).data('id');

                            // تحديث حالة قراءة التنبيه
                            $.ajax({
                                url: `/notifications/${id}/mark-as-read`,
                                method: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function() {
                                    // تحديث عدد التنبيهات غير المقروءة
                                    loadUnreadCount();

                                    // تحديث قائمة التنبيهات
                                    loadLatestNotifications();
                                }
                            });
                        });
                    }
                });
            }

            // تحميل التنبيهات عند تحميل الصفحة
            loadUnreadCount();

            // تحميل التنبيهات عند فتح القائمة المنسدلة
            $('#notificationsDropdown').on('click', function() {
                loadLatestNotifications();
            });

            // تحديد جميع التنبيهات كمقروءة
            $('#mark-all-read').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $.ajax({
                    url: "{{ route('notifications.mark-all-as-read') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // تحديث عدد التنبيهات غير المقروءة
                        loadUnreadCount();

                        // تحديث قائمة التنبيهات
                        loadLatestNotifications();

                        // عرض رسالة نجاح
                        toastr.success('تم تحديد جميع التنبيهات كمقروءة');
                    }
                });
            });

            // تحديث التنبيهات كل دقيقة
            setInterval(function() {
                loadUnreadCount();
            }, 60000);
        });

        // تفعيل مودل تعديل معلومات الطبيب
        document.addEventListener('DOMContentLoaded', function() {
            const editProfileBtn = document.getElementById('editProfileBtn');
            if (editProfileBtn) {
                editProfileBtn.addEventListener('click', function() {
                    const editDoctorProfileModal = new bootstrap.Modal(document.getElementById('editDoctorProfileModal'));
                    editDoctorProfileModal.show();
                });
            }
        });
    </script>
    @yield('scripts')

    <!-- مودل تعديل معلومات الطبيب والعيادة -->
    <div class="modal fade" id="editDoctorProfileModal" tabindex="-1" aria-labelledby="editDoctorProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: #f0f7ff;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="editDoctorProfileModalLabel" style="font-size: 24px; color: #0077B6;">معلومات الطبيب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <form id="editDoctorProfileForm" action="{{ route('doctor.update-profile') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-4">
                                            <div class="col-md-3 text-md-end">
                                                <label for="name" class="form-label fw-bold" style="color: #0077B6;">الاسم</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control bg-light" id="name" name="name" value="{{ $currentDoctor->name }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-3 text-md-end">
                                                <label for="specialty" class="form-label fw-bold" style="color: #0077B6;">الاختصاص</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control bg-light" id="specialty" name="specialty" value="{{ $currentDoctor->specialty ? $currentDoctor->specialty->name : 'جراحة وزراعة الأسنان' }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-3 text-md-end">
                                                <label for="phone" class="form-label fw-bold" style="color: #0077B6;">رقم الهاتف</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control bg-light" id="phone" name="phone" value="{{ $currentDoctor->phone }}" required>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-3 text-md-end">
                                                <label for="code" class="form-label fw-bold" style="color: #0077B6;">الرمز</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control bg-light" id="code" name="password" placeholder="أدخل كلمة المرور الجديدة (اتركها فارغة للاحتفاظ بكلمة المرور الحالية)">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-3 text-md-end">
                                                <label for="code_confirmation" class="form-label fw-bold" style="color: #0077B6;">تأكيد الرمز</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="password" class="form-control bg-light" id="code_confirmation" name="password_confirmation" placeholder="تأكيد كلمة المرور الجديدة">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="profile-image-container">
                                <img src="{{ asset('images/11.png') }}" alt="صورة الطبيب" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;">
                                <div class="mt-3">
                                    <a href="#" class="btn btn-sm btn-outline-primary">تعديل الصورة</a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">إزالة الصور</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-5 mb-4" style="font-size: 24px; color: #0077B6;">معلومات العيادة</h5>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="editClinicProfileForm" action="{{ route('doctor.update-clinic') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="clinic_name" class="form-label fw-bold" style="color: #0077B6;">اسم العيادة</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="clinic_name" name="name" value="{{ $currentClinic->name }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label for="address" class="form-label fw-bold" style="color: #0077B6;">العنوان</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control bg-light" id="address" name="address" value="{{ $currentClinic->address }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3 text-md-end">
                                        <label class="form-label fw-bold" style="color: #0077B6;">وقت الدوام</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-5">
                                                <input type="time" class="form-control bg-light" name="opening_time" value="{{ $currentClinic->opening_time ? $currentClinic->opening_time->format('H:i') : '04:00' }}" required>
                                            </div>
                                            <div class="col-2 text-center">
                                                <span class="form-label fw-bold">الى</span>
                                            </div>
                                            <div class="col-5">
                                                <input type="time" class="form-control bg-light" name="closing_time" value="{{ $currentClinic->closing_time ? $currentClinic->closing_time->format('H:i') : '10:00' }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary px-5 py-2" style="background-color: #0077B6; border-color: #0077B6;" onclick="document.getElementById('editDoctorProfileForm').submit(); document.getElementById('editClinicProfileForm').submit();">حفظ التغييرات</button>
                        <button type="button" class="btn btn-outline-secondary px-5 py-2 ms-2" data-bs-dismiss="modal">الغاء</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

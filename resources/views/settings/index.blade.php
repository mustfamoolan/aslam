@extends('layouts.settings_layout')

@section('title', 'إعدادات النظام')

@section('content')
<!-- كارد إعدادات النظام -->
<div class="settings-card">
    <div class="settings-card-header">
        <h2 class="settings-card-title">إعدادات النظام</h2>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <div class="settings-text">تفعيل الإشعارات</div>
                <div class="settings-description">استلام إشعارات عن المواعيد والتحديثات</div>
            </div>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notificationsSwitch" checked>
        </div>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div>
                <div class="settings-text">تفعيل التحديثات التلقائية</div>
                <div class="settings-description">تحديث البيانات تلقائياً</div>
            </div>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="autoUpdateSwitch" checked>
        </div>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="settings-text">تذكير قبل الموعد بمدة</div>
                <div class="settings-description">إرسال تذكير قبل الموعد</div>
            </div>
        </div>
        <div class="time-selector">
            <button class="time-btn">10 دقائق</button>
            <button class="time-btn active">5 دقائق</button>
        </div>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-palette"></i>
            </div>
            <div>
                <div class="settings-text">المظهر</div>
                <div class="settings-description">اختيار مظهر النظام</div>
            </div>
        </div>
        <div class="theme-selector">
            <div class="theme-btn light active" title="فاتح"></div>
            <div class="theme-btn dark" title="داكن"></div>
        </div>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-language"></i>
            </div>
            <div>
                <div class="settings-text">تغيير اللغة</div>
                <div class="settings-description">اختيار لغة النظام</div>
            </div>
        </div>
        <div class="language-selector">
            <button class="language-btn">
                العربية
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</div>

<!-- كارد إعدادات الأمان -->
<div class="settings-card">
    <div class="settings-card-header">
        <h2 class="settings-card-title">إعدادات الأمان</h2>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-database"></i>
            </div>
            <div>
                <div class="settings-text">تفعيل النسخ الاحتياطي للبيانات</div>
                <div class="settings-description">عمل نسخة احتياطية من البيانات بشكل دوري</div>
            </div>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="backupSwitch">
        </div>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-laptop"></i>
            </div>
            <div>
                <div class="settings-text">الأجهزة المرتبطة بالنظام</div>
                <div class="settings-description">إدارة الأجهزة المتصلة بحسابك</div>
            </div>
        </div>
        <div>
            <span>جهاز 1</span>
            <i class="fas fa-chevron-left"></i>
        </div>
    </div>
</div>

<!-- كارد مركز المساعدة -->
<div class="settings-card">
    <div class="settings-card-header">
        <h2 class="settings-card-title">مركز المساعدة</h2>
    </div>

    <div class="settings-item">
        <div class="settings-label">
            <div class="settings-icon">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <div class="settings-text">التواصل مع فريق الدعم</div>
                <div class="settings-description">للمساعدة والاستفسارات</div>
            </div>
        </div>
        <div>
            <i class="fas fa-chevron-left"></i>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تفعيل أزرار الوقت
        $('.time-btn').click(function() {
            $('.time-btn').removeClass('active');
            $(this).addClass('active');
        });

        // تفعيل أزرار المظهر
        $('.theme-btn').click(function() {
            $('.theme-btn').removeClass('active');
            $(this).addClass('active');

            if ($(this).hasClass('dark')) {
                // تطبيق المظهر الداكن
                $('body').addClass('dark-theme');
            } else {
                // تطبيق المظهر الفاتح
                $('body').removeClass('dark-theme');
            }
        });
    });
</script>
@endsection

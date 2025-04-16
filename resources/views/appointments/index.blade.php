@extends('layouts.appointments_layout')

@section('title', 'إدارة المواعيد')

@section('styles')
<style>
    .appointments-table {
        width: 100%;
        background-color: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .appointments-table table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .appointments-table th {
        background-color: #f8fafc;
        padding: 18px 15px;
        font-weight: 500;
        color: #34939C;
        text-align: center;
        border-bottom: 1px solid #edf2f7;
        font-size: 14px;
    }

    .appointments-table td {
        padding: 15px 12px;
        text-align: center;
        border-bottom: 1px solid #f0f4f8;
        vertical-align: middle;
        font-size: 14px;
        color: #22577A;
    }

    .appointments-table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .appointments-table tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .appointments-table tr:hover {
        background-color: #f0f7fa !important;
    }

    .appointments-table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        background-color: #00c853;
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        display: inline-block;
        font-weight: 500;
    }

    .status-badge.bg-warning {
        background-color: #ffb300;
    }

    .status-badge.bg-danger {
        background-color: #f44336;
    }

    .star-icon {
        color: #ddd;
        cursor: pointer;
        font-size: 16px;
        margin-left: 5px;
        transition: all 0.2s ease;
    }

    .star-icon:hover, .star-icon.active {
        color: #ffc107;
        transform: scale(1.2);
    }

    .folder-icon {
        color: #34939C;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .folder-icon:hover, .folder-icon.active {
        color: #22577A;
        transform: scale(1.2);
    }

    .patient-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f0f0f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .table-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 18px 20px;
        background-color: #f8fafc;
        border-top: 1px solid #edf2f7;
        position: relative;
    }

    .checkbox-container {
        display: flex;
        justify-content: center;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 0;
        cursor: pointer;
        border-color: #d1d5db;
    }

    .form-check-input:checked {
        background-color: #34939C;
        border-color: #34939C;
    }

    .time-cell {
        color: #22577A;
        font-size: 13px;
        font-weight: 500;
    }

    .date-cell {
        color: #22577A;
        font-size: 13px;
        font-weight: 500;
    }

    .session-type {
        color: #34939C;
        font-weight: 500;
    }

    .patient-name {
        font-weight: 500;
        color: #22577A;
    }

    .patient-name a {
        text-decoration: none;
        color: #22577A;
        transition: color 0.2s;
    }

    .patient-name a:hover {
        color: #34939C;
        text-decoration: underline;
    }

    .patient-age {
        color: #22577A;
    }

    .table-header {
        background-color: #f8fafc;
    }

    .table-header th {
        font-weight: 500;
        color: #34939C;
        border-bottom: none;
        padding: 18px 15px;
    }

    .action-btn {
        background-color: #f0f0f0;
        border: none;
        color: #555;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 13px;
    }

    .action-btn:hover {
        background-color: #e0e0e0;
    }

    .footer-actions {
        display: flex;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .footer-actions .action-btn {
        flex: 1;
        text-align: center;
        background-color: transparent;
        border: none;
        padding: 10px 0;
        font-size: 16px;
        font-weight: 500;
        color: #007ED0;
    }

    .footer-actions .action-btn:first-child {
        border-left: 1px solid #eee;
    }

    .footer-actions .action-btn i {
        display: none;
    }

    /* أنماط مودال التصفية */
    .filter-modal {
        position: absolute;
        top: 70px;
        left: 20px;
        width: 280px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        padding: 20px;
        display: none;
    }

    .filter-modal.show {
        display: block;
    }

    .filter-modal-header {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
        color: #22577A;
        font-size: 16px;
    }

    .filter-option {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        text-align: center;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .filter-option:hover {
        background-color: #eef2f7;
    }

    .filter-option.active {
        background-color: #34939C;
        color: white;
    }

    .filter-option.disabled {
        color: #ccc;
        cursor: not-allowed;
    }

    .filter-option .filter-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
    }

    .filter-option .filter-chevron {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
    }

    .filter-submenu {
        display: none;
        margin-top: 5px;
        margin-bottom: 15px;
        padding-right: 15px;
    }

    .filter-submenu.show {
        display: block;
    }

    .filter-submenu-item {
        background-color: #f0f0f0;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 5px;
        text-align: center;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
    }

    .filter-submenu-item:hover {
        background-color: #e0e0e0;
    }

    .filter-submenu-item.active {
        background-color: #22577A;
        color: white;
    }

    .filter-actions {
        margin-top: 20px;
        text-align: center;
    }

    .filter-clear {
        color: #f44336;
        background: none;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 14px;
    }

    .filter-clear:hover {
        text-decoration: underline;
    }

    .filter-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.1);
        z-index: 999;
        display: none;
    }

    .filter-overlay.show {
        display: block;
    }

    /* أنماط المودال */
    .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }

    .modal-title {
        font-weight: 600;
        color: #22577A;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 12px;
    }

    .modal-footer {
        border-top: none;
        padding-top: 0;
    }

    .btn-primary {
        background-color: #34939C;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
    }

    .btn-secondary {
        background-color: #f0f0f0;
        border: none;
        color: #555;
        border-radius: 8px;
        padding: 8px 20px;
    }

    .pagination-container {
        position: absolute;
        right: 20px;
    }

    .appointment-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .appointment-row:hover {
        background-color: #f0f7fa !important;
    }

    .appointment-row td:last-child,
    .appointment-row td:first-child {
        cursor: default;
    }

    /* أنماط مودال إضافة موعد جديد */
    .appointment-modal {
        border-radius: 20px;
        overflow: hidden;
        background-color: #f8f9fe;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .appointment-modal .modal-header {
        background-color: #f8f9fe;
        border-bottom: none;
        padding: 25px 30px 15px;
    }

    .appointment-modal .modal-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.5rem;
        text-align: right;
        width: 100%;
    }

    .appointment-modal .modal-body {
        padding: 0 30px 20px;
    }

    .appointment-form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .appointment-form-col {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
    }

    .search-patient-container {
        margin-bottom: 20px;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-patient-input {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e6ed;
        background-color: #fff;
        color: #2c3e50;
        font-size: 1rem;
        padding-left: 50px;
        width: 100%;
    }

    .search-btn {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 50px;
        background-color: #34929B;
        border: none;
        border-radius: 10px 0 0 10px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .appointment-modal .form-label {
        color: #0077B6;
        font-weight: 500;
        margin-bottom: 10px;
        font-size: 1.1rem;
        text-align: right;
        display: block;
    }

    .appointment-modal .form-control,
    .appointment-modal .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e6ed;
        background-color: #fff;
        color: #2c3e50;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .appointment-modal .form-control:focus,
    .appointment-modal .form-select:focus {
        border-color: #0077B6;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .appointment-modal .form-control::placeholder {
        color: #95a5a6;
        opacity: 0.7;
    }

    .appointment-modal textarea.form-control {
        resize: none;
        height: 150px;
        background-color: #eef5ff;
    }

    .appointment-modal .modal-footer {
        border-top: none;
        padding: 15px 30px 25px;
        justify-content: center;
        gap: 20px;
    }

    .appointment-modal .btn-primary {
        background-color: #0077B6;
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s;
        min-width: 150px;
    }

    .appointment-modal .btn-primary:hover {
        background-color: #0077B6;
        transform: translateY(-2px);
    }

    .appointment-modal .btn-secondary {
        background-color: #ecf0f1;
        border: none;
        color: #7f8c8d;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s;
        min-width: 150px;
    }

    .appointment-modal .btn-secondary:hover {
        background-color: #bdc3c7;
        color: #2c3e50;
    }

    /* أنماط للحقول المخصصة */
    .patient-select-wrapper,
    .date-select-wrapper,
    .time-select-wrapper,
    .session-select-wrapper {
        position: relative;
    }

    .patient-select-wrapper:after,
    .date-select-wrapper:after,
    .time-select-wrapper:after,
    .session-select-wrapper:after {
        content: "\f078";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #3498db;
        pointer-events: none;
    }

    /* أنماط التاريخ والوقت */
    .date-picker-container,
    .time-picker-container {
        position: relative;
    }

    .date-display, .time-display {
        cursor: pointer;
        background-color: #f8f9fe;
        text-align: right;
    }

    .date-picker-popup, .time-picker-popup {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        width: 300px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow: hidden;
        margin-top: 5px;
    }

    .date-picker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
    }

    .month-nav {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0 10px;
    }

    .weekdays-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        padding: 10px 0;
        background-color: #f8f9fe;
        font-size: 0.8rem;
        font-weight: bold;
        color: #7f8c8d;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        padding: 10px;
    }

    .calendar-day {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
    }

    .calendar-day:hover {
        background-color: #e0f7fa;
    }

    .calendar-day.selected {
        background-color: #3498db;
        color: white;
    }

    .calendar-day.today {
        border: 2px solid #3498db;
    }

    .calendar-day.other-month {
        color: #bdc3c7;
    }

    /* أنماط حقل الوقت */
    .time-picker-popup {
        width: 250px;
        max-height: 300px;
        overflow-y: auto;
    }

    .time-slots {
        padding: 10px;
    }

    .time-slot {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 5px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .time-slot:hover {
        background-color: #e0f7fa;
    }

    .time-slot.selected {
        background-color: #3498db;
        color: white;
    }

    /* أنماط للأوقات غير المتاحة */
    .time-slot.unavailable {
        background-color: #f8f9fe;
        color: #bdc3c7;
        cursor: not-allowed;
        text-decoration: line-through;
        opacity: 0.7;
    }

    /* أنماط لمؤشر التحميل */
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        color: #3498db;
    }

    .loading-spinner i {
        margin-left: 10px;
    }

    /* أنماط لرسالة الخطأ */
    .error-message {
        color: #e74c3c;
        text-align: center;
        padding: 20px;
    }

    /* أنماط لنتائج البحث */
    .search-results-container {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 0 0 5px 5px;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-top: 2px;
    }

    .search-result-item {
        padding: 10px 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .search-result-item:hover {
        background-color: #f5f8fb;
    }

    .search-result-item.no-results,
    .search-result-item.error,
    .search-result-item.loading {
        text-align: center;
        color: #666;
        padding: 15px;
        cursor: default;
    }

    .search-result-item.error {
        color: #e74c3c;
    }

    .patient-search-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-left: 10px;
        background-color: #edf2f7;
    }

    .patient-search-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .patient-search-info {
        flex: 1;
    }

    .patient-search-name {
        font-weight: 600;
        color: #1e5a7e;
        margin-bottom: 3px;
    }

    .patient-search-details {
        font-size: 12px;
        color: #666;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- مودال التصفية -->
    <div class="filter-overlay" id="filterOverlay"></div>
    <div class="filter-modal" id="filterModal">
        <div class="filter-modal-header">تصفية الجدول حسب</div>

        <!-- تصفية حسب اسم المريض -->
        <div class="filter-option" data-filter="patient" data-has-submenu="true">
            اسم المريض
            <i class="fas fa-chevron-down filter-chevron"></i>
        </div>
        <div class="filter-submenu" id="patientSubmenu">
            @if(isset($patients) && count($patients) > 0)
                @foreach($patients as $patient)
                    <div class="filter-submenu-item" data-filter="patient" data-value="{{ $patient->id }}">
                        {{ $patient->full_name }}
                    </div>
                @endforeach
            @else
                <div class="filter-submenu-item disabled">لا يوجد مرضى</div>
            @endif
        </div>

        <!-- تصفية حسب نوع الجلسة -->
        <div class="filter-option" data-filter="session_type" data-has-submenu="true">
            نوع الجلسة
            <i class="fas fa-chevron-down filter-chevron"></i>
        </div>
        <div class="filter-submenu" id="sessionTypeSubmenu">
            <div class="filter-submenu-item" data-filter="session_type" data-value="مراجعة ثانية">مراجعة ثانية</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="حشوة">حشوة</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="تنظيف">تنظيف</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="خلع">خلع</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="تقويم">تقويم</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="زراعة">زراعة</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="تبييض">تبييض</div>
            <div class="filter-submenu-item" data-filter="session_type" data-value="كشف">كشف</div>
        </div>

        <!-- تصفية حسب التاريخ -->
        <div class="filter-option" data-filter="date">
            <div class="d-flex justify-content-between align-items-center">
                <span>التاريخ</span>
                <input type="date" class="form-control filter-date-input" style="width: 150px; height: 30px;">
            </div>
        </div>

        <!-- تصفية حسب الوقت -->
        <div class="filter-option" data-filter="time">
            <div class="d-flex justify-content-between align-items-center">
                <span>الوقت</span>
                <input type="time" class="form-control filter-time-input" style="width: 150px; height: 30px;">
            </div>
        </div>

        <!-- تصفية حسب الحالة -->
        <div class="filter-option" data-filter="status" data-has-submenu="true">
            حسب الحالة
            <i class="fas fa-chevron-down filter-chevron"></i>
        </div>
        <div class="filter-submenu" id="statusSubmenu">
            <div class="filter-submenu-item" data-filter="status" data-value="pending">قيد الانتظار</div>
            <div class="filter-submenu-item" data-filter="status" data-value="completed">مكتمل</div>
            <div class="filter-submenu-item" data-filter="status" data-value="cancelled">ملغي</div>
        </div>

        <!-- تصفية المؤرشفة والمميزة -->
        <div class="filter-option" data-filter="archived">المؤرشفة فقط</div>
        <div class="filter-option" data-filter="starred">المميزة فقط</div>

        <div class="filter-actions">
            <button class="filter-clear">مسح التصفية</button>
        </div>
    </div>

    <div class="appointments-table">
        <table class="table mb-0">
            <thead class="table-header">
                <tr>
                    <th style="width: 40px;">ت</th>
                    <th style="width: 60px;">الصورة</th>
                    <th style="width: 150px;">اسم المريض</th>
                    <th style="width: 80px;">عمر المريض</th>
                    <th style="width: 150px;">نوع الجلسة</th>
                    <th style="width: 120px;">تاريخ الموعد</th>
                    <th style="width: 120px;">وقت الموعد</th>
                    <th style="width: 120px;">حالة الموعد</th>
                    <th style="width: 100px;">إعدادات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr data-id="{{ $appointment->id }}"
                    data-patient-id="{{ $appointment->patient->id ?? 0 }}"
                    class="{{ $appointment->is_archived ? 'archived' : '' }} {{ $appointment->is_starred ? 'starred' : '' }} appointment-row">
                    <td>
                        <div class="checkbox-container">
                            <input type="checkbox" class="form-check-input appointment-checkbox" value="{{ $appointment->id }}">
                        </div>
                    </td>
                    <td>
                        @if($appointment->patient && $appointment->patient->gender == 'male')
                            <img src="{{ asset('images/11.png') }}" alt="صورة المريض" class="patient-img">
                        @else
                            <img src="{{ asset('images/22.png') }}" alt="صورة المريضة" class="patient-img">
                        @endif
                    </td>
                    <td class="patient-name">
                        {{ $appointment->patient->full_name ?? 'غير محدد' }}
                    </td>
                    <td class="patient-age">{{ $appointment->patient->age ?? 'غير محدد' }}</td>
                    <td class="session-type">{{ $appointment->session_type ?? 'مراجعة' }}</td>
                    <td class="date-cell">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y/m/d') }}</td>
                    <td class="time-cell">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                    <td>
                        <span class="status-badge
                            @if($appointment->status == 'completed') bg-success
                            @elseif($appointment->status == 'cancelled') bg-danger
                            @else bg-warning @endif">
                            {{ $appointment->status == 'completed' ? 'مكتمل' : ($appointment->status == 'cancelled' ? 'ملغي' : 'قيد الانتظار') }}
                        </span>
                    </td>
                    <td>
                        <i class="fas fa-star star-icon {{ $appointment->is_starred ? 'active' : '' }}"
                           data-id="{{ $appointment->id }}" title="تمييز بنجمة"></i>
                        <i class="fas fa-archive folder-icon {{ $appointment->is_archived ? 'active' : '' }}"
                           data-id="{{ $appointment->id }}" title="أرشفة"></i>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">لا توجد مواعيد متاحة</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer">
            <div class="footer-actions">
                <button class="action-btn" id="starSelectedBtn">
                    <i class="fas fa-star"></i>
                    تمييز بنجمة
                </button>
                <button class="action-btn" id="archiveSelectedBtn">
                    <i class="fas fa-folder"></i>
                    أرشفة
                </button>
            </div>
            <div class="pagination-container">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة موعد جديد -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content appointment-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="addAppointmentModalLabel">إضافة موعد جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAppointmentForm" action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    <!-- حقل البحث عن المريض -->
                    <div class="search-patient-container mb-4">
                        <div class="search-input-wrapper">
                            <input type="text" class="form-control search-patient-input" id="patient_search" placeholder="ابحث عن مريض لحجز موعد له">
                            <button type="button" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="appointment-form-row">
                        <div class="appointment-form-col">
                            <!-- حقول الجانب الأيمن -->
                            <div class="mb-4">
                                <label for="patient_id" class="form-label">اسم المريض</label>
                                <div class="patient-select-wrapper">
                                    <select class="form-select" id="patient_id" name="patient_id" required>
                                        <option value="" selected disabled>قم باختياره من قائمة البحث</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- حقل التاريخ -->
                            <div class="mb-4">
                                <label for="appointment_date" class="form-label">تاريخ الموعد</label>
                                <div class="date-picker-container">
                                    <input type="text" class="form-control date-display" id="date_display" placeholder="اضغط لاختياره" readonly>
                                    <input type="hidden" id="appointment_date" name="appointment_date" required>
                                    <div class="date-picker-popup" id="date_picker_popup">
                                        <div class="date-picker-header">
                                            <button type="button" class="month-nav prev-month">&lt;</button>
                                            <div class="current-month">October 2024</div>
                                            <button type="button" class="month-nav next-month">&gt;</button>
                                        </div>
                                        <div class="weekdays-header">
                                            <div>SUN</div>
                                            <div>MON</div>
                                            <div>TUE</div>
                                            <div>WED</div>
                                            <div>THU</div>
                                            <div>FRI</div>
                                            <div>SAT</div>
                                        </div>
                                        <div class="calendar-days" id="calendar_days">
                                            <!-- سيتم إنشاء الأيام بواسطة JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- حقل الوقت -->
                            <div class="mb-4">
                                <label for="appointment_time" class="form-label">وقت الموعد</label>
                                <div class="time-picker-container">
                                    <input type="text" class="form-control time-display" id="time_display" placeholder="اضغط لاختياره" readonly>
                                    <input type="hidden" id="appointment_time" name="appointment_time" required>
                                    <div class="time-picker-popup" id="time_picker_popup">
                                        <div class="time-slots">
                                            <div class="time-slot" data-time="09:00">09:00 AM</div>
                                            <div class="time-slot" data-time="09:30">09:30 AM</div>
                                            <div class="time-slot" data-time="10:00">10:00 AM</div>
                                            <div class="time-slot" data-time="10:30">10:30 AM</div>
                                            <div class="time-slot" data-time="11:00">11:00 AM</div>
                                            <div class="time-slot" data-time="11:30">11:30 AM</div>
                                            <div class="time-slot" data-time="12:00">12:00 PM</div>
                                            <div class="time-slot" data-time="12:30">12:30 PM</div>
                                            <div class="time-slot" data-time="13:00">01:00 PM</div>
                                            <div class="time-slot" data-time="13:30">01:30 PM</div>
                                            <div class="time-slot" data-time="14:00">02:00 PM</div>
                                            <div class="time-slot" data-time="14:30">02:30 PM</div>
                                            <div class="time-slot" data-time="15:00">03:00 PM</div>
                                            <div class="time-slot" data-time="15:30">03:30 PM</div>
                                            <div class="time-slot" data-time="16:00">04:00 PM</div>
                                            <div class="time-slot" data-time="16:30">04:30 PM</div>
                                            <div class="time-slot" data-time="17:00">05:00 PM</div>
                                            <div class="time-slot" data-time="17:30">05:30 PM</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="session_type" class="form-label">نوع الجلسة</label>
                                <div class="session-select-wrapper">
                                    <select class="form-select" id="session_type" name="session_type">
                                        <option value="" selected disabled>اضغط لاختياره</option>
                                        <option value="فحص أولي">فحص أولي</option>
                                        <option value="حشوة أسنان">حشوة أسنان</option>
                                        <option value="علاج عصب">علاج عصب</option>
                                        <option value="تنظيف أسنان">تنظيف أسنان</option>
                                        <option value="خلع سن">خلع سن</option>
                                        <option value="تركيب تقويم">تركيب تقويم</option>
                                        <option value="متابعة تقويم">متابعة تقويم</option>
                                        <option value="تركيب طربوش">تركيب طربوش</option>
                                        <option value="تبييض أسنان">تبييض أسنان</option>
                                        <option value="زراعة أسنان">زراعة أسنان</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="form-label">المبلغ</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="اكتب المبلغ">
                            </div>
                        </div>

                        <div class="appointment-form-col">
                            <!-- حقول الجانب الأيسر -->
                            <div class="mb-4">
                                <label for="note" class="form-label">ملاحظة</label>
                                <textarea class="form-control" id="note" name="note" rows="6" placeholder="اكتب ملاحظة سريعة وهامة"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="session_title" class="form-label">عنوان الجلسة</label>
                                <input type="text" class="form-control" id="session_title" name="session_title" placeholder="عنوان الجلسة">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">إضافة الموعد</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودال تفاصيل المريض -->
<div class="modal fade" id="patientDetailsModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light py-3">
                <h5 class="modal-title fw-bold text-primary" id="patientDetailsModalLabel">عنوان الموعد أو الجلسة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card mb-4 border-0">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">اسم المريض</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="patient-name">اسلام</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">عمر المريض</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="patient-age">20</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">وقت الموعد</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="appointment-time">10:00 AM</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">تاريخ الموعد</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="appointment-date">10 / 10 / 2024</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">المبلغ الكلي</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="total-amount">100 ألف</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">نوع المدفوع</div>
                                    <div class="bg-light py-1 px-3 rounded flex-grow-1" id="payment-type">100</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2" style="width: 120px;">الحالة</div>
                                    <div class="py-1 px-3 rounded flex-grow-1">
                                        <span id="appointment-status" class="badge bg-success px-4 py-2">مدفوع</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="d-flex mb-3">
                                    <div class="text-primary fw-bold ms-2">ملاحظة</div>
                                </div>
                                <div class="bg-light p-3 rounded mb-4" id="appointment-note">
                                    تم استخدام حشوة ضوئية.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-light w-100 text-start py-2 mb-2" id="dental-images-btn">
                                    <i class="fas fa-chevron-left float-start mt-1"></i>
                                    صور الأسنان
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-light w-100 text-start py-2 mb-2" id="xray-images-btn">
                                    <i class="fas fa-chevron-left float-start mt-1"></i>
                                    صور الأشعة
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="text-primary fw-bold mb-2">تفاصيل الجلسة</div>
                                <div class="bg-light p-3 rounded mb-4" id="session-details">
                                    تم إزالة التسوس من الضرس الأيمن السفلي.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-primary fw-bold mb-2">الدواء الذي تم وصفه</div>
                                <div class="bg-light p-3 rounded mb-3" id="prescribed-medicine">
                                    إيبوبروفين 400 ملجم حبة واحدة كل 8 ساعات.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-primary fw-bold mb-2">تعليمات الاستخدام</div>
                                <div class="bg-light p-3 rounded mb-3" id="usage-instructions">
                                    تناول الدواء بعد الطعام لتجنب تهيج المعدة لمدة 3 أيام.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="button" class="btn btn-primary px-5 py-2" id="editAppointmentBtn">تعديل</button>
                    <button type="button" class="btn btn-outline-danger px-5 py-2" id="deleteAppointmentBtn">حذف</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // تفعيل وظيفة النجمة
    document.querySelectorAll('.star-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            toggleStar(appointmentId, this);
        });
    });

    // تفعيل وظيفة الأرشفة
    document.querySelectorAll('.folder-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            toggleArchive(appointmentId, this);
        });
    });

    // تفعيل مودال التصفية
    document.addEventListener('DOMContentLoaded', function() {
        const filterIcon = document.querySelector('.filter-icon');
        const filterModal = document.getElementById('filterModal');
        const filterOverlay = document.getElementById('filterOverlay');

        // فتح المودال عند الضغط على أيقونة الفلتر
        if (filterIcon) {
            filterIcon.addEventListener('click', function() {
                filterModal.classList.toggle('show');
                filterOverlay.classList.toggle('show');
            });
        }

        // إغلاق المودال عند الضغط خارجه
        if (filterOverlay) {
            filterOverlay.addEventListener('click', function() {
                filterModal.classList.remove('show');
                filterOverlay.classList.remove('show');
            });
        }

        // تفعيل خيارات التصفية الرئيسية
        const filterOptions = document.querySelectorAll('.filter-option:not(.disabled)');
        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const hasSubmenu = this.getAttribute('data-has-submenu') === 'true';

                if (hasSubmenu) {
                    // إذا كان له قائمة فرعية، نعرضها أو نخفيها
                    const filterType = this.getAttribute('data-filter');
                    const submenu = document.getElementById(filterType + 'Submenu');
                    if (submenu) {
                        submenu.classList.toggle('show');
                        // تغيير اتجاه السهم
                        const chevron = this.querySelector('.filter-chevron');
                        if (chevron) {
                            chevron.classList.toggle('fa-chevron-down');
                            chevron.classList.toggle('fa-chevron-up');
                        }
                    }
                } else if (this.querySelector('input')) {
                    // إذا كان يحتوي على حقل إدخال، لا نفعل شيئًا عند النقر على الخيار نفسه
                    return;
                } else {
                    // تفعيل/إلغاء تفعيل الخيار
                    this.classList.toggle('active');
                    applyFilters();
                }
            });
        });

        // تفعيل حقول الإدخال في خيارات التصفية
        const dateInput = document.querySelector('.filter-date-input');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                applyFilters();
            });

            // منع انتشار الحدث للعنصر الأب
            dateInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        const timeInput = document.querySelector('.filter-time-input');
        if (timeInput) {
            timeInput.addEventListener('change', function() {
                applyFilters();
            });

            // منع انتشار الحدث للعنصر الأب
            timeInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // تفعيل خيارات القوائم الفرعية
        const submenuItems = document.querySelectorAll('.filter-submenu-item');
        submenuItems.forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
                applyFilters();
            });
        });

        // مسح التصفية
        const filterClearBtn = document.querySelector('.filter-clear');
        if (filterClearBtn) {
            filterClearBtn.addEventListener('click', function() {
                // إلغاء تفعيل جميع خيارات التصفية
                document.querySelectorAll('.filter-option.active').forEach(option => {
                    option.classList.remove('active');
                });

                // إلغاء تفعيل جميع خيارات القوائم الفرعية
                document.querySelectorAll('.filter-submenu-item.active').forEach(item => {
                    item.classList.remove('active');
                });

                // إعادة تعيين حقول الإدخال
                if (dateInput) dateInput.value = '';
                if (timeInput) timeInput.value = '';

                // إعادة عرض جميع الصفوف
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = '';
                });
            });
        }

        // تفعيل خيارات التصفية الإضافية
        const statusSubmenu = document.getElementById('statusSubmenu');
        if (statusSubmenu) {
            const statusItems = statusSubmenu.querySelectorAll('.filter-submenu-item');
            statusItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('active');
                    applyFilters();
                });
            });
        }

        // تفعيل خيارات التصفية الإضافية
        const sessionTypeSubmenu = document.getElementById('sessionTypeSubmenu');
        if (sessionTypeSubmenu) {
            const sessionTypeItems = sessionTypeSubmenu.querySelectorAll('.filter-submenu-item');
            sessionTypeItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('active');
                    applyFilters();
                });
            });
        }

        // تفعيل خيارات التصفية الإضافية
        const patientSubmenu = document.getElementById('patientSubmenu');
        if (patientSubmenu) {
            const patientItems = patientSubmenu.querySelectorAll('.filter-submenu-item');
            patientItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('active');
                    applyFilters();
                });
            });
        }

        // تفعيل خيارات التصفية الإضافية
        const archived = document.querySelector('.filter-option[data-filter="archived"]');
        if (archived) {
            archived.addEventListener('click', function() {
                this.classList.toggle('active');
                applyFilters();
            });
        }

        // تفعيل خيارات التصفية الإضافية
        const starred = document.querySelector('.filter-option[data-filter="starred"]');
        if (starred) {
            starred.addEventListener('click', function() {
                this.classList.toggle('active');
                applyFilters();
            });
        }
    });

    // وظيفة تطبيق الفلاتر
    function applyFilters() {
        // الحصول على الفلاتر النشطة
        const activeMainFilters = Array.from(document.querySelectorAll('.filter-option.active')).map(option =>
            option.getAttribute('data-filter')
        );

        const activeSubmenuFilters = {};
        document.querySelectorAll('.filter-submenu-item.active').forEach(item => {
            const filterType = item.getAttribute('data-filter');
            const filterValue = item.getAttribute('data-value');

            if (!activeSubmenuFilters[filterType]) {
                activeSubmenuFilters[filterType] = [];
            }

            activeSubmenuFilters[filterType].push(filterValue);
        });

        const dateFilter = document.querySelector('.filter-date-input')?.value;
        const timeFilter = document.querySelector('.filter-time-input')?.value;

        // إعادة عرض جميع الصفوف
        document.querySelectorAll('tbody tr').forEach(row => {
            let shouldShow = true;

            // تطبيق الفلاتر الرئيسية
            if (activeMainFilters.includes('archived') && !row.classList.contains('archived')) {
                shouldShow = false;
            }

            if (activeMainFilters.includes('starred') && !row.classList.contains('starred')) {
                shouldShow = false;
            }

            // تطبيق فلاتر القوائم الفرعية
            for (const filterType in activeSubmenuFilters) {
                if (activeSubmenuFilters[filterType].length > 0) {
                    const cellValue = row.querySelector(`.${filterType}-cell`)?.textContent.trim();
                    const rowValue = row.getAttribute(`data-${filterType}`);
                    const valueToCheck = rowValue || cellValue;

                    if (!activeSubmenuFilters[filterType].some(value => valueToCheck?.includes(value))) {
                        shouldShow = false;
                    }
                }
            }

            // تطبيق فلتر التاريخ
            if (dateFilter) {
                const rowDate = row.querySelector('.date-cell')?.textContent.trim();
                const formattedRowDate = formatDateForComparison(rowDate);
                if (formattedRowDate !== dateFilter) {
                    shouldShow = false;
                }
            }

            // تطبيق فلتر الوقت
            if (timeFilter) {
                const rowTime = row.querySelector('.time-cell')?.textContent.trim();
                const formattedRowTime = formatTimeForComparison(rowTime);
                if (formattedRowTime !== timeFilter) {
                    shouldShow = false;
                }
            }

            // تطبيق النتيجة
            row.style.display = shouldShow ? '' : 'none';
        });
    }

    // وظائف مساعدة لتنسيق التاريخ والوقت للمقارنة
    function formatDateForComparison(dateStr) {
        if (!dateStr) return '';

        // تحويل التاريخ من صيغة "2024/10/10" إلى "2024-10-10"
        const parts = dateStr.split('/');
        if (parts.length === 3) {
            return `${parts[0]}-${parts[1]}-${parts[2]}`;
        }

        return dateStr;
    }

    function formatTimeForComparison(timeStr) {
        if (!timeStr) return '';

        // تحويل الوقت من صيغة "10:26 AM" إلى "10:26"
        const timeParts = timeStr.split(' ')[0];
        return timeParts;
    }

    // وظيفة تبديل حالة النجمة
    function toggleStar(appointmentId, element) {
        fetch(`/appointments/${appointmentId}/toggle-star`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.classList.toggle('active');
                const row = element.closest('tr');
                if (data.is_starred) {
                    row.classList.add('starred');
                } else {
                    row.classList.remove('starred');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // وظيفة تبديل حالة الأرشفة
    function toggleArchive(appointmentId, element) {
        fetch(`/appointments/${appointmentId}/toggle-archive`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.classList.toggle('active');
                const row = element.closest('tr');
                if (data.is_archived) {
                    row.classList.add('archived');
                } else {
                    row.classList.remove('archived');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // الحصول على المواعيد المحددة
    function getSelectedAppointments() {
        const checkboxes = document.querySelectorAll('.appointment-checkbox:checked');
        return Array.from(checkboxes).map(checkbox => checkbox.value);
    }

    // تمييز مواعيد متعددة بنجمة
    function starMultipleAppointments(appointmentIds) {
        fetch('/appointments/star-multiple', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ appointment_ids: appointmentIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // أرشفة مواعيد متعددة
    function archiveMultipleAppointments(appointmentIds) {
        fetch('/appointments/archive-multiple', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ appointment_ids: appointmentIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // إضافة كود JavaScript لمعالجة إرسال نموذج إضافة موعد
    document.addEventListener('DOMContentLoaded', function() {
        const addAppointmentButton = document.getElementById('addAppointmentButton');
        if (addAppointmentButton) {
            addAppointmentButton.addEventListener('click', function() {
                const addAppointmentModal = new bootstrap.Modal(document.getElementById('addAppointmentModal'));
                addAppointmentModal.show();
            });
        }

        const saveAppointmentBtn = document.getElementById('saveAppointmentBtn');
        if (saveAppointmentBtn) {
            saveAppointmentBtn.addEventListener('click', function() {
                const form = document.getElementById('addAppointmentForm');
                const formData = new FormData(form);

                // تحويل FormData إلى كائن JSON
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                // إرسال البيانات باستخدام Fetch API
                fetch('{{ route("appointments.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // إغلاق المودال
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addAppointmentModal'));
                        modal.hide();

                        // عرض رسالة نجاح
                        alert('تم إضافة الموعد بنجاح');

                        // إعادة تحميل الصفحة بعد فترة قصيرة
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // عرض رسائل الخطأ
                        alert('حدث خطأ أثناء إضافة الموعد');
                        console.error(data.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إضافة الموعد');
                });
            });
        }
    });

    // إضافة كود JavaScript لمعالجة عرض تفاصيل المريض
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل الضغط على الصف بأكمله لعرض تفاصيل المريض
        const appointmentRows = document.querySelectorAll('.appointment-row');

        appointmentRows.forEach(row => {
            row.addEventListener('click', function(e) {
                // تجاهل النقر على خلية الاختيار أو خلية الأيقونات
                if (e.target.closest('td:first-child') || e.target.closest('td:last-child') ||
                    e.target.classList.contains('form-check-input') ||
                    e.target.classList.contains('star-icon') ||
                    e.target.classList.contains('folder-icon')) {
                    return;
                }

                const patientId = this.getAttribute('data-patient-id');
                showPatientDetails(patientId);
            });
        });

        // وظيفة عرض تفاصيل المريض
        function showPatientDetails(patientId) {
            // في الحالة الحقيقية، يجب استدعاء API للحصول على بيانات المريض
            // لكن هنا سنستخدم بيانات ثابتة للعرض

            // يمكنك استبدال هذا بطلب AJAX للحصول على البيانات من الخادم
            // fetch(`/patients/${patientId}/appointment-details`)
            //    .then(response => response.json())
            //    .then(data => {
            //        // تعبئة البيانات في المودال
            //    });

            // عرض المودال
            const patientDetailsModal = new bootstrap.Modal(document.getElementById('patientDetailsModal'));
            patientDetailsModal.show();

            // تفعيل أزرار المودال
            document.getElementById('editAppointmentBtn').addEventListener('click', function() {
                // تنفيذ إجراء التعديل
                patientDetailsModal.hide();
                // يمكن فتح مودال التعديل هنا
            });

            document.getElementById('deleteAppointmentBtn').addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا الموعد؟')) {
                    // تنفيذ إجراء الحذف
                    // fetch(`/appointments/${appointmentId}`, {
                    //    method: 'DELETE',
                    //    headers: {
                    //        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    //    }
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //    if (data.success) {
                    //        window.location.reload();
                    //    }
                    // });
                }
            });

            // تفعيل أزرار الصور
            document.getElementById('dental-images-btn').addEventListener('click', function() {
                // عرض صور الأسنان
                alert('عرض صور الأسنان');
            });

            document.getElementById('xray-images-btn').addEventListener('click', function() {
                // عرض صور الأشعة
                alert('عرض صور الأشعة');
            });
        }
    });

    // تفعيل حقول التاريخ والوقت المخصصة
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل حقل التاريخ
        const dateDisplay = document.getElementById('date_display');
        const dateInput = document.getElementById('appointment_date');
        const datePicker = document.getElementById('date_picker_popup');
        const calendarDays = document.getElementById('calendar_days');
        const currentMonthElement = document.querySelector('.current-month');
        const prevMonthBtn = document.querySelector('.prev-month');
        const nextMonthBtn = document.querySelector('.next-month');

        let currentDate = new Date();
        let selectedDate = null;

        // عرض التقويم عند النقر على حقل التاريخ
        if (dateDisplay) {
            dateDisplay.addEventListener('click', function() {
                datePicker.style.display = 'block';
                renderCalendar(currentDate);
            });

            // إغلاق التقويم عند النقر خارجه
            document.addEventListener('click', function(e) {
                if (!dateDisplay.contains(e.target) && !datePicker.contains(e.target)) {
                    datePicker.style.display = 'none';
                }
            });

            // أزرار التنقل بين الشهور
            prevMonthBtn.addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });

            nextMonthBtn.addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
        }

        // وظيفة لعرض التقويم
        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();

            // تحديث عنوان الشهر
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            currentMonthElement.textContent = `${monthNames[month]} ${year}`;

            // الحصول على اليوم الأول من الشهر
            const firstDay = new Date(year, month, 1);
            const startingDay = firstDay.getDay(); // 0 = الأحد، 1 = الاثنين، إلخ

            // الحصول على عدد أيام الشهر
            const lastDay = new Date(year, month + 1, 0);
            const totalDays = lastDay.getDate();

            // الحصول على عدد أيام الشهر السابق
            const prevMonthLastDay = new Date(year, month, 0).getDate();

            // تفريغ التقويم
            calendarDays.innerHTML = '';

            // إضافة أيام الشهر السابق
            for (let i = startingDay - 1; i >= 0; i--) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day', 'other-month');
                dayElement.textContent = prevMonthLastDay - i;
                calendarDays.appendChild(dayElement);
            }

            // إضافة أيام الشهر الحالي
            const today = new Date();
            for (let i = 1; i <= totalDays; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = i;

                // تحديد اليوم الحالي
                if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
                    dayElement.classList.add('today');
                }

                // تحديد التاريخ المحدد
                if (selectedDate && year === selectedDate.getFullYear() && month === selectedDate.getMonth() && i === selectedDate.getDate()) {
                    dayElement.classList.add('selected');
                }

                // عند النقر على يوم
                dayElement.addEventListener('click', function() {
                    // إزالة التحديد السابق
                    const selectedDays = document.querySelectorAll('.calendar-day.selected');
                    selectedDays.forEach(day => day.classList.remove('selected'));

                    // تحديد اليوم الجديد
                    dayElement.classList.add('selected');

                    // تحديث التاريخ المحدد
                    selectedDate = new Date(year, month, i);

                    // تحديث حقل التاريخ
                    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    dateInput.value = formattedDate;

                    // تحديث العرض
                    const displayFormat = `${String(i).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
                    dateDisplay.value = displayFormat;

                    // تحديث الأوقات المتاحة
                    updateAvailableTimes(formattedDate);

                    // إغلاق التقويم
                    datePicker.style.display = 'none';
                });

                calendarDays.appendChild(dayElement);
            }

            // إضافة أيام الشهر التالي
            const totalCells = 42; // 6 صفوف × 7 أعمدة
            const remainingCells = totalCells - (startingDay + totalDays);

            for (let i = 1; i <= remainingCells; i++) {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day', 'other-month');
                dayElement.textContent = i;
                calendarDays.appendChild(dayElement);
            }
        }

        // تفعيل حقل الوقت
        const timeDisplay = document.getElementById('time_display');
        const timeInput = document.getElementById('appointment_time');
        const timePicker = document.getElementById('time_picker_popup');
        const timeSlots = document.querySelectorAll('.time-slot');

        if (timeDisplay) {
            // عرض قائمة الأوقات عند النقر على حقل الوقت
            timeDisplay.addEventListener('click', function() {
                timePicker.style.display = 'block';
            });

            // إغلاق قائمة الأوقات عند النقر خارجها
            document.addEventListener('click', function(e) {
                if (!timeDisplay.contains(e.target) && !timePicker.contains(e.target)) {
                    timePicker.style.display = 'none';
                }
            });

            // عند اختيار وقت
            timeSlots.forEach(slot => {
                slot.addEventListener('click', function() {
                    // إزالة التحديد السابق
                    timeSlots.forEach(s => s.classList.remove('selected'));

                    // تحديد الوقت الجديد
                    slot.classList.add('selected');

                    // تحديث حقل الوقت
                    const time = slot.getAttribute('data-time');
                    timeInput.value = time;

                    // تحديث العرض
                    timeDisplay.value = slot.textContent;

                    // إغلاق قائمة الأوقات
                    timePicker.style.display = 'none';
                });
            });
        }

        // الحصول على قائمة الأوقات بناءً على ساعات العيادة عند تحميل الصفحة
        $.ajax({
            url: "{{ route('get-clinic-hours') }}",
            method: "GET",
            dataType: 'json',
            success: function(clinicHours) {
                console.log('تم استلام ساعات العيادة بنجاح:', clinicHours);
                // إنشاء قائمة الأوقات وعرضها في مودال إضافة موعد جديد
                createTimeOptions(clinicHours);
            },
            error: function(xhr, status, error) {
                console.error('حدث خطأ أثناء استلام ساعات العيادة:', error);
                console.error('استجابة الخادم:', xhr.responseText);
                // استخدام قيم افتراضية في حالة حدوث خطأ
                alert('تعذر الحصول على ساعات عمل العيادة. سيتم استخدام القيم الافتراضية (9:00 صباحاً - 5:30 مساءً).');
                createTimeOptions({ opening_time: '09:00', closing_time: '17:30' });
            }
        });

        // دالة لإنشاء خيارات الوقت في قائمة منسدلة
        function createTimeOptions(clinicHours) {
            const timePickerPopup = document.getElementById('time_picker_popup');
            const startTimeString = clinicHours.opening_time || '09:00';
            const endTimeString = clinicHours.closing_time || '17:30';

            // تحويل الأوقات إلى دقائق
            const startMinutes = convertTimeToMinutes(startTimeString);
            const endMinutes = convertTimeToMinutes(endTimeString);
            const interval = 30; // 30 دقيقة

            let timeSlotHtml = '<div class="time-slots">';

            // إنشاء فتحات الوقت بفاصل 30 دقيقة
            for (let minutes = startMinutes; minutes <= endMinutes; minutes += interval) {
                const timeString = convertMinutesToTime(minutes);
                const formattedTime = formatTimeAMPM(timeString);
                timeSlotHtml += `<div class="time-slot" data-time="${timeString}">${formattedTime}</div>`;
            }

            timeSlotHtml += '</div>';

            // تحديث قائمة الأوقات في المودال
            if (timePickerPopup) {
                timePickerPopup.innerHTML = timeSlotHtml;

                // إضافة مستمعي الأحداث إلى فتحات الوقت
                const timeSlots = timePickerPopup.querySelectorAll('.time-slot');
                const timeDisplay = document.getElementById('time_display');

                timeSlots.forEach(slot => {
                    slot.addEventListener('click', function() {
                        // إزالة التحديد السابق
                        timeSlots.forEach(s => s.classList.remove('selected'));

                        // تحديد الوقت الجديد
                        slot.classList.add('selected');

                        // تحديث حقل الوقت
                        const time = slot.getAttribute('data-time');
                        document.getElementById('appointment_time').value = time;

                        // تحديث العرض
                        timeDisplay.value = slot.textContent;

                        // إغلاق قائمة الأوقات
                        timePickerPopup.style.display = 'none';
                    });
                });
            }
        }

        // إزالة الكود القديم للتحقق من الأوقات المتاحة
        // وإبقاء دالة updateAvailableTimes فارغة لأننا لن نستخدمها
        window.updateAvailableTimes = function(date) {
            // لا نحتاج للتحقق من الأوقات المتاحة
            console.log("تم اختيار التاريخ:", date);
        };
    });

    // دالة مساعدة لتحويل الوقت إلى دقائق
    function convertTimeToMinutes(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // دالة مساعدة لتحويل الدقائق إلى وقت
    function convertMinutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
    }

    // دالة مساعدة لتنسيق الوقت بصيغة AM/PM
    function formatTimeAMPM(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const displayHours = hours % 12 || 12;
        return `${String(displayHours).padStart(2, '0')}:${String(minutes).padStart(2, '0')} ${ampm}`;
    }
</script>
@endsection


@extends('layouts.patient')

@section('title', 'مواعيد المريض')

@section('content')
<!-- مربع البحث -->
<div class="search-container">
    <div class="search-bar">
        <input type="text" placeholder="ابحث عن موعد..." id="appointmentSearch">
        <button type="button">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<div class="content-wrapper">
    <!-- جدول المواعيد -->
    <div class="appointments-container">
        <div class="appointments-table-container">
            <h2 class="section-title">الجلسات</h2>

            <div class="table-responsive">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>عنوان الموعد</th>
                            <th>تاريخ الموعد</th>
                            <th>وقت الموعد</th>
                            <th>الحالة</th>
                            <th>تمييز</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointments->count() > 0)
                            @foreach($appointments as $index => $appointment)
                                <tr data-appointment-id="{{ $appointment->id }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="appointment-title">
                                            <span>{{ $appointment->session_type ?? 'موعد جديد' }}</span>
                                            <a href="javascript:void(0);" class="details-btn" onclick="editAppointment({{ $appointment->id }}); return false;">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                </div>
                            </td>
                                    <td>{{ $appointment->appointment_date->format('d / m / Y') }}</td>
                                    <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                                    <td>
                                        @php
                                            $statusClass = '';
                                            $statusText = '';

                                            switch($appointment->status) {
                                                case 'completed':
                                                    $statusClass = 'completed';
                                                    $statusText = 'مكتمل';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'pending';
                                                    $statusText = 'قادم';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'cancelled';
                                                    $statusText = 'ملغي';
                                                    break;
                                                default:
                                                    $statusClass = 'pending';
                                                    $statusText = 'قادم';
                                            }
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('appointments.toggle-star', $appointment->id) }}" class="star-form" onsubmit="return false;">
                                    @csrf
                                    <button type="button" class="star-btn" onclick="submitStarForm(this)">
                                        @if($appointment->is_starred)
                                    <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                </button>
                                </form>
                            </td>
                        </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">لا توجد مواعيد مسجلة لهذا المريض</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="sidebar-container">
        <!-- كارد الملاحظات -->
        <div class="notes-container">
            <div class="notes-card">
                <div class="notes-header">
                    <h3>ملاحظات</h3>
                    <button class="add-note-btn" id="openAddNoteModal">إضافة</button>
                </div>
                <div class="notes-list">
                    <div class="note-item">
                        <div class="note-content">
                            <p>عضو ب سوا لذلك عناية خاصة اللثة</p>
                            <span class="note-date">6 / 12 / 2024</span>
                        </div>
                        <div class="note-number">1</div>
                    </div>
                    <div class="note-item">
                        <div class="note-content">
                            <p>علاج التهاب اللثة</p>
                            <span class="note-date">6 / 12 / 2024</span>
                        </div>
                        <div class="note-number">2</div>
                    </div>
                    <div class="note-item">
                        <div class="note-content">
                            <p>علاج التهاب اللثة</p>
                            <span class="note-date">6 / 12 / 2024</span>
                        </div>
                        <div class="note-number">3</div>
                    </div>
                    <div class="note-item">
                        <div class="note-content">
                            <p>علاج التهاب اللثة</p>
                            <span class="note-date">6 / 12 / 2024</span>
                        </div>
                        <div class="note-number">4</div>
                    </div>
                    <div class="note-item">
                        <div class="note-content">
                            <p>علاج التهاب اللثة</p>
                            <span class="note-date">6 / 12 / 2024</span>
                        </div>
                        <div class="note-number">5</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- مساحة فارغة صغيرة -->
        <div class="small-spacer"></div>

        <!-- كارد تصفية الجدول -->
        <div class="filter-container">
            <div class="filter-card">
                <div class="filter-header">
                    <h3>تصفية الجدول حسب</h3>
                </div>
                <div class="filter-options">
                    <div class="filter-row">
                        <button class="filter-btn">التاريخ</button>
                        <button class="filter-btn">عنوان الموعد</button>
                    </div>
                    <div class="filter-row">
                        <button class="filter-btn">الوقت</button>
                        <button class="filter-btn">المميزة بنجمة فقط</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- مساحة فارغة -->
        <div class="spacer"></div>

        <!-- زر إضافة موعد جديد -->
        <a href="#" class="add-appointment-btn" id="openAddAppointmentModal">
            <i class="fas fa-plus-circle"></i>
            إضافة موعد جديد
        </a>
    </div>
</div>

<!-- مودال إضافة ملاحظة جديدة -->
<div class="modal-overlay" id="addNoteModal">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-header">
                <h3>إضافة ملاحظة جديدة</h3>
            </div>
            <div class="modal-body">
                <form id="addNoteForm" method="POST" action="{{ route('patient-notes.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="noteTitle">عنوان الملاحظة</label>
                        <input type="text" id="noteTitle" name="title" placeholder="اكتب عنوان الملاحظة" class="form-control" required>
                        <div class="note-date-display" id="currentDate"></div>
                    </div>

                    <div class="form-group">
                        <label for="noteContent">محتوى الملاحظة</label>
                        <textarea id="noteContent" name="content" placeholder="أكتب ملاحظتك" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="important-checkbox">
                            <input type="checkbox" id="isImportant" name="is_important" value="1">
                            <span class="important-label">ملاحظة مهمة</span>
                        </label>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-cancel" id="closeAddNoteModal">الغاء</button>
                        <button type="submit" class="btn btn-save">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة موعد جديد -->
<div class="modal-overlay" id="addAppointmentModal">
    <div class="modal-container appointment-modal">
        <div class="modal-content appointment-content">
            <div class="modal-header appointment-header">
                <h3>عنوان الموعد أو الجلسة</h3>
            </div>
            <div class="modal-body">
                <form id="addAppointmentForm" method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <div class="appointment-form-grid">
                        <div class="appointment-form-column">
                            <div class="form-group">
                                <div class="form-label">تاريخ الموعد</div>
                                <div class="date-picker-container">
                                    <input type="text" class="form-control appointment-input" id="appointmentDate" placeholder="اختر التاريخ" readonly>
                                    <button type="button" class="dropdown-btn" id="datePickerBtn">اضغط لاختياره</button>
                                    <i class="fas fa-chevron-down dropdown-icon"></i>

                                    <!-- تقويم اختيار التاريخ -->
                                    <div class="date-picker-popup" id="datePickerPopup">
                                        <div class="date-picker-header">
                                            <button type="button" class="month-nav prev-month">&lt;</button>
                                            <div class="current-month" id="currentMonth">October 2024</div>
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
                                        <div class="calendar-days" id="calendarDays">
                                            <!-- سيتم إنشاء الأيام بواسطة JavaScript -->
                                        </div>
                                    </div>
                            </div>
                        </div>

                            <div class="form-group">
                                <div class="form-label">وقت الموعد</div>
                                <div class="time-picker-container">
                                    <input type="text" class="form-control appointment-input" id="appointmentTime" placeholder="اختر الوقت" readonly>
                                    <button type="button" class="dropdown-btn" id="timePickerBtn">اضغط لاختياره</button>
                                    <i class="fas fa-chevron-down dropdown-icon"></i>

                                    <!-- قائمة اختيار الوقت -->
                                    <div class="time-picker-popup" id="timePickerPopup">
                                        <div class="time-slots-header">الأوقات المتوفرة</div>
                                        <div class="time-slots">
                                            <div class="time-slot">7:00 PM</div>
                                            <div class="time-slot">7:30 PM</div>
                                            <div class="time-slot">8:00 PM</div>
                                            <div class="time-slot">8:30 PM</div>
                                            <div class="time-slot">9:00 PM</div>
                                            <div class="time-slot">9:30 PM</div>
                                            <div class="time-slot">10:00 PM</div>
                                            <div class="time-slot">10:30 PM</div>
                                            <div class="time-slot">11:00 PM</div>
                                            <div class="time-slot">11:30 PM</div>
                                            <div class="time-slot">12:00 AM</div>
                                            <div class="time-slot">12:30 AM</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">المبلغ المدفوع</div>
                                <input type="text" class="form-control appointment-input" id="appointmentAmount" placeholder="اكتبه" value="">
                            </div>

                            <div class="form-group">
                                <div class="form-label">نوع الجلسة</div>
                                <div class="session-type-container">
                                    <input type="text" class="form-control appointment-input" id="appointmentType" placeholder="اختر نوع الجلسة" readonly>
                                    <button type="button" class="dropdown-btn" id="sessionTypeBtn">اضغط لاختياره</button>
                                    <i class="fas fa-chevron-down dropdown-icon"></i>

                                    <!-- قائمة أنواع الجلسات -->
                                    <div class="session-type-popup" id="sessionTypePopup">
                                        <div class="session-types">
                                            <div class="session-type">فحص أولي</div>
                                            <div class="session-type">حشوة أسنان</div>
                                            <div class="session-type">علاج عصب</div>
                                            <div class="session-type">تنظيف أسنان</div>
                                            <div class="session-type">خلع سن</div>
                                            <div class="session-type">تركيب تقويم</div>
                                            <div class="session-type">متابعة تقويم</div>
                                            <div class="session-type">تركيب طربوش</div>
                                            <div class="session-type">تبييض أسنان</div>
                                            <div class="session-type">زراعة أسنان</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-label">الحــــالــــة</div>
                                <div class="status-selector">
                                    <button type="button" class="status-btn active">مكتمل</button>
                                </div>
                            </div>
                        </div>

                        <div class="appointment-form-column">
                            <div class="form-group">
                                <div class="form-label">ملاحظة</div>
                                <textarea class="form-control appointment-textarea" id="appointmentNote" placeholder="أضف ملاحظتك"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group session-details">
                        <div class="form-label">تفاصيل الجلسة</div>
                        <textarea class="form-control appointment-textarea" id="appointmentDetails" placeholder="تم إزالة التسوس من الضرس الأيمن السفلي."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <div class="form-label">الدواء الذي تم وصفه</div>
                            <textarea class="form-control appointment-textarea" id="appointmentMedicine" placeholder="إيبوبروفين 400 ملجم حبة واحدة كل 8 ساعات."></textarea>
                        </div>

                        <div class="form-group half">
                            <div class="form-label">تعليمات الاستخدام</div>
                            <textarea class="form-control appointment-textarea" id="appointmentInstructions" placeholder="تناول الدواء بعد الطعام لتجنب تهيج المعدة لمدة 3 أيام."></textarea>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-cancel" id="closeAddAppointmentModal">الغاء</button>
                        <button type="submit" class="btn btn-save">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- إضافة طبقة خلفية داكنة للقوائم المنسدلة -->
<div class="popup-backdrop" id="popupBackdrop"></div>

<style>
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
        background-color: #38A3A5;
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
        background-color: #2C8385;
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

    /* تنسيق الصف الرئيسي */
    .content-wrapper {
        display: flex;
        width: 100%;
        padding: 0 20px;
        gap: 20px;
        align-items: flex-start;
    }

    /* تنسيق جدول المواعيد */
    .appointments-container {
        flex: 1;
        display: flex;
        justify-content: flex-start;
        width: 100%;
        padding: 0 20px;
        height: calc(100vh - 150px); /* ارتفاع يمتد حتى نهاية الصفحة تقريبًا */
    }

    .appointments-table-container {
        background-color: white;
        border-radius: 15px;
        padding: 20px;
        margin: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 1000px;
        max-width: 100%;
        height: 100%; /* يأخذ كامل ارتفاع الحاوية */
        display: flex;
        flex-direction: column;
    }

    .section-title {
        color: #22577A;
        font-size: 20px;
        margin: 0 0 20px 0;
    }

    /* تخصيص شكل شريط التمرير */
    .table-responsive::-webkit-scrollbar {
        width: 10px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #38A3A5;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #2C8385;
    }

    .table-responsive {
        overflow-y: auto;
        flex: 1;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .appointments-table th {
        background-color: #f5f7fa;
        padding: 15px;
        text-align: right;
        border-bottom: 1px solid #eee;
        position: sticky; /* جعل رأس الجدول ثابتًا */
        top: 0; /* في أعلى منطقة التمرير */
        z-index: 10; /* فوق باقي العناصر */
    }

    .appointments-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        color: #666;
    }

    .appointments-table tr:hover {
        background-color: #f9f9f9;
    }

    .appointments-table .text-center {
        text-align: center;
    }

    .appointment-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .details-btn {
        color: #38A3A5;
        font-size: 16px;
        transition: transform 0.2s ease;
    }

    .details-btn:hover {
        transform: scale(1.2);
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.completed {
        background-color: #e8f5e9;
        color: #4caf50;
    }

    .status-badge.pending {
        background-color: #fff8e1;
        color: #ffc107;
    }

    .status-badge.cancelled {
        background-color: #ffebee;
        color: #f44336;
    }

    .star-btn {
        background: none;
        border: none;
        color: #ffc107;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.2s ease;
        padding: 5px;
        border-radius: 50%;
    }

    .star-btn:hover {
        transform: scale(1.2);
        background-color: rgba(255, 193, 7, 0.1);
    }

    .star-btn .fas.fa-star {
        color: #ffc107; /* لون النجمة المملوءة */
    }

    .star-btn .far.fa-star {
        color: #ccc; /* لون النجمة الفارغة */
    }

    /* تنسيق الشريط الجانبي */
    .sidebar-container {
        width: 300px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        height: calc(100vh - 150px);
        position: relative;
    }

    /* تنسيق كارد الملاحظات */
    .notes-container {
        width: 100%;
    }

    .notes-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        height: 500px; /* تحديد ارتفاع ثابت 500px */
        display: flex;
        flex-direction: column;
    }

    .notes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .notes-header h3 {
        color: #22577A;
        font-size: 18px;
        margin: 0;
    }

    .add-note-btn {
        background-color: #38A3A5;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 15px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .add-note-btn:hover {
        background-color: #2C8385;
    }

    .notes-list {
        overflow-y: auto;
        padding: 0;
        flex: 1;
    }

    /* تخصيص شكل شريط التمرير */
    .notes-list::-webkit-scrollbar {
        width: 5px;
    }

    .notes-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notes-list::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }

    .notes-list::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }

    .note-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
        flex-direction: row-reverse;
    }

    .note-item:hover {
        background-color: #f9f9f9;
    }

    .note-content {
        flex: 1;
        text-align: right;
        padding-right: 15px;
    }

    .note-content p {
        margin: 0 0 5px 0;
        color: #444;
        font-size: 14px;
        font-weight: 500;
    }

    .note-date {
        color: #888;
        font-size: 12px;
        display: block;
        text-align: right;
    }

    .note-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background-color: #e9f5f5;
        color: #38A3A5;
        border-radius: 50%;
        font-size: 14px;
        font-weight: 600;
    }

    /* مساحة فارغة صغيرة */
    .small-spacer {
        height: 30px;
    }

    /* تنسيق كارد التصفية */
    .filter-container {
        width: 100%;
    }

    .filter-card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .filter-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .filter-header h3 {
        color: #22577A;
        font-size: 18px;
        margin: 0;
    }

    .filter-options {
        padding: 15px;
    }

    .filter-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .filter-row:last-child {
        margin-bottom: 0;
    }

    .filter-btn {
        flex: 1;
        background-color: #f5f7fa;
        border: none;
        border-radius: 8px;
        padding: 12px 15px;
        color: #22577A;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .filter-btn:hover {
        background-color: #e9f5f5;
    }

    .filter-btn.active {
        background-color: #e9f5f5;
        color: #38A3A5;
        font-weight: 600;
    }

    /* مساحة فارغة */
    .spacer {
        flex: 1;
        min-height: 50px;
    }

    /* تنسيق زر إضافة موعد جديد */
    .add-appointment-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        border: 2px dashed #38A3A5;
        border-radius: 8px;
        color: #38A3A5;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        background-color: rgba(56, 163, 165, 0.05);
        gap: 10px;
        margin-bottom: 20px;
    }

    .add-appointment-btn:hover {
        background-color: rgba(56, 163, 165, 0.1);
        transform: translateY(-2px);
    }

    .add-appointment-btn i {
        font-size: 20px;
    }

    /* تنسيق المودال */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background-color: white;
        border-radius: 15px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-header h3 {
        color: #22577A;
        font-size: 20px;
        margin: 0;
        text-align: center;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Alexandria', sans-serif;
        font-size: 16px;
        color: #444;
        text-align: right;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #38A3A5;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    textarea.form-control {
        min-height: 150px;
        resize: none;
    }

    .note-date-display {
        position: absolute;
        left: 15px;
        top: 15px;
        color: #888;
        font-size: 14px;
    }

    .modal-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-save {
        background-color: #38A3A5;
        color: white;
    }

    .btn-save:hover {
        background-color: #2C8385;
    }

    .btn-cancel {
        background-color: #f5f5f5;
        color: #666;
    }

    .btn-cancel:hover {
        background-color: #e0e0e0;
    }

    /* تنسيق مودال إضافة موعد */
    .appointment-modal {
        width: 800px;
        max-width: 95%;
    }

    .appointment-content {
        background-color: #f5f9ff;
        border-radius: 15px;
        padding: 0;
        overflow: hidden;
    }

    .appointment-header {
        background-color: #f5f9ff;
        border-bottom: none;
        padding: 20px 30px;
    }

    .appointment-header h3 {
        color: #22577A;
        font-size: 22px;
        font-weight: 700;
        text-align: center;
    }

    .appointment-form-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .appointment-form-column {
        flex: 1;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group.half {
        flex: 1;
    }

    .form-label {
        display: block;
        color: #22577A;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
        text-align: right;
    }

    .appointment-input,
    .appointment-textarea {
        background-color: #e9f1fd;
        border: none;
        border-radius: 8px;
        padding: 15px;
        width: 100%;
        color: #333;
        font-size: 14px;
        text-align: left;
        direction: ltr;
    }

    .appointment-textarea {
        min-height: 150px;
        resize: none;
    }

    .status-selector {
        display: flex;
    }

    .status-btn {
        flex: 1;
        padding: 12px;
        background-color: #00C853;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .session-details .form-label,
    .form-group.half .form-label {
        color: #22577A;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .modal-body {
        padding: 20px 30px 30px;
    }

    /* تخصيص أزرار الحفظ والإلغاء */
    .appointment-modal .modal-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .appointment-modal .btn {
        padding: 12px 40px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .appointment-modal .btn-save {
        background-color: #38A3A5;
        color: white;
    }

    .appointment-modal .btn-save:hover {
        background-color: #2C8385;
    }

    .appointment-modal .btn-cancel {
        background-color: #f5f5f5;
        color: #666;
    }

    .appointment-modal .btn-cancel:hover {
        background-color: #e0e0e0;
    }

    /* تنسيق حقول القوائم المنسدلة */
    .date-picker-container, .time-picker-container, .session-type-container {
        position: relative;
        display: flex;
        align-items: center;
        background-color: #e9f1fd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .date-picker-container .appointment-input,
    .time-picker-container .appointment-input,
    .session-type-container .appointment-input {
        flex: 1;
        background: transparent;
        border-radius: 0;
        padding: 15px;
        border: none;
        color: #333;
        font-size: 14px;
    }

    .dropdown-btn {
        background-color: #e9f1fd;
        color: #38A3A5;
        border: none;
        padding: 0 15px;
        height: 100%;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: background-color 0.2s;
    }

    .dropdown-btn:hover {
        background-color: #d8e6f9;
    }

    .dropdown-icon {
        position: absolute;
        left: 10px;
        color: #38A3A5;
        font-size: 12px;
        pointer-events: none;
    }

    /* تنسيق تقويم اختيار التاريخ */
    .date-picker-popup {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        z-index: 996 !important;
        padding: 20px;
        max-height: 80vh;
        overflow-y: auto;
        width: 350px;
        display: none;
    }

    .date-picker-popup.active {
        display: block !important;
    }

    .date-picker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eaeaea;
    }

    .current-month {
        font-weight: 600;
        color: #22577A;
        font-size: 16px;
    }

    .month-nav {
        background: none;
        border: none;
        color: #38A3A5;
        cursor: pointer;
        font-size: 16px;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.2s;
    }

    .month-nav:hover {
        background-color: #f0f0f0;
    }

    .weekdays-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        margin-bottom: 10px;
    }

    .weekdays-header div {
        font-size: 12px;
        color: #888;
        padding: 5px 0;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .calendar-day {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 14px;
        color: #444;
        transition: all 0.2s;
        margin: 0 auto;
    }

    .calendar-day:hover {
        background-color: #e9f5f5;
    }

    .calendar-day.selected {
        background-color: #38A3A5;
        color: white;
    }

    .calendar-day.today {
        border: 2px solid #38A3A5;
    }

    .calendar-day.other-month {
        color: #ccc;
    }

    /* تنسيق قائمة اختيار الوقت */
    .time-picker-popup {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        z-index: 996 !important;
        padding: 20px;
        max-height: 80vh;
        overflow-y: auto;
        width: 350px;
        display: none;
    }

    .time-picker-popup.active {
        display: block !important;
    }

    .time-slots-header {
        text-align: center;
        font-weight: 600;
        color: #22577A;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eaeaea;
    }

    .time-slots {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        max-height: 250px;
        overflow-y: auto;
    }

    .time-slot {
        text-align: center;
        padding: 8px;
        background-color: #f5f9ff;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
    }

    .time-slot:hover {
        background-color: #e9f5f5;
    }

    .time-slot.selected {
        background-color: #38A3A5;
        color: white;
    }

    /* تنسيق قائمة أنواع الجلسات */
    .session-type-popup {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        z-index: 996 !important;
        padding: 20px;
        max-height: 80vh;
        overflow-y: auto;
        width: 350px;
        display: none;
    }

    .session-type-popup.active {
        display: block !important;
    }

    .session-types {
        max-height: 250px;
        overflow-y: auto;
    }

    .session-type {
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .session-type:hover {
        background-color: #e9f5f5;
    }

    .session-type.selected {
        background-color: #38A3A5;
        color: white;
    }

    /* إضافة طبقة خلفية داكنة */
    .popup-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 995; /* قيمة أقل من القوائم المنسدلة */
        display: none;
    }

    .popup-backdrop.active {
        display: block;
    }

    /* جعل قوائم التقويم والأوقات وأنواع الجلسات فوق الطبقة السوداء */
    .date-picker-popup,
    .time-picker-popup,
    .session-type-popup {
        z-index: 996; /* قيمة أعلى من الطبقة السوداء */
        position: absolute;
        display: none;
    }

    .date-picker-popup.active,
    .time-picker-popup.active,
    .session-type-popup.active {
        display: block;
        pointer-events: auto; /* السماح بالتفاعل مع عناصر القائمة */
    }

    /* تأكد من أن جميع عناصر التقويم قابلة للنقر */
    .calendar-days,
    .calendar-day,
    .time-slot,
    .session-type {
        cursor: pointer;
        pointer-events: auto; /* السماح بالنقر */
    }

    /* تنسيق للأيام المحددة */
    .calendar-day.selected {
        background-color: #38A3A5;
        color: white;
    }
</style>

<script>
    document.getElementById('appointmentSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.appointments-table tbody tr');

        rows.forEach(row => {
            const title = row.querySelector('.appointment-title span').textContent.toLowerCase();
            const date = row.querySelectorAll('td')[2].textContent.toLowerCase();

            if (title.includes(searchTerm) || date.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    document.querySelector('.search-bar button').addEventListener('click', function() {
        const searchTerm = document.getElementById('appointmentSearch').value;
        console.log('تم النقر على زر البحث:', searchTerm);
        // تنفيذ البحث (نفس الكود الموجود في حدث input)
        const rows = document.querySelectorAll('.appointments-table tbody tr');

        rows.forEach(row => {
            const title = row.querySelector('.appointment-title span').textContent.toLowerCase();
            const date = row.querySelectorAll('td')[2].textContent.toLowerCase();

            if (title.includes(searchTerm.toLowerCase()) || date.includes(searchTerm.toLowerCase())) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // تفعيل أزرار النجمة
    document.querySelectorAll('.star-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('fas')) {
                icon.classList.replace('fas', 'far');
            } else {
                icon.classList.replace('far', 'fas');
            }
        });
    });

    // تفعيل أزرار التصفية
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            // إزالة الفئة النشطة من جميع الأزرار في نفس الصف
            const row = this.closest('.filter-row');
            row.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // إضافة الفئة النشطة للزر المضغوط
            this.classList.add('active');

            // هنا يمكن إضافة منطق التصفية الفعلي
            console.log('تصفية حسب:', this.textContent);
        });
    });

    // الحصول على التاريخ الحالي بتنسيق مناسب
    function getCurrentDate() {
        const today = new Date();
        const day = today.getDate();
        const month = today.getMonth() + 1; // الشهور تبدأ من 0
        const year = today.getFullYear();
        return `${day} / ${month} / ${year}`;
    }

    // تفعيل مودال إضافة ملاحظة
    const modal = document.getElementById('addNoteModal');
    const openModalBtn = document.getElementById('openAddNoteModal');
    const closeModalBtn = document.getElementById('closeAddNoteModal');
    const addNoteForm = document.getElementById('addNoteForm');
    const currentDateElement = document.getElementById('currentDate');

    // فتح المودال وعرض التاريخ الحالي
    openModalBtn.addEventListener('click', function() {
        currentDateElement.textContent = getCurrentDate();
        modal.classList.add('active');
    });

    // إغلاق المودال
    closeModalBtn.addEventListener('click', function() {
        modal.classList.remove('active');
    });

    // إغلاق المودال عند النقر خارجه
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // منع إعادة تحميل الصفحة عند إرسال النموذج
    addNoteForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // جمع بيانات النموذج
        const noteTitleElement = document.getElementById('noteTitle');
        const noteContentElement = document.getElementById('noteContent');
        const isImportantElement = document.getElementById('isImportant');

        // طباعة معلومات التصحيح
        console.log("عنصر عنوان الملاحظة:", noteTitleElement);
        console.log("قيمة العنوان:", noteTitleElement ? noteTitleElement.value : 'العنصر غير موجود');

        // التحقق من وجود العناصر
        if (!noteTitleElement) {
            alert('لم يتم العثور على حقل عنوان الملاحظة');
            return;
        }

        const noteTitle = noteTitleElement.value.trim();
        const noteContent = noteContentElement ? noteContentElement.value.trim() : '';
        const isImportant = isImportantElement ? isImportantElement.checked : false;

        // التحقق من وجود عنوان
        if (!noteTitle) {
            alert('يرجى إدخال عنوان للملاحظة');
            return;
        }

        console.log("بيانات الملاحظة التي سيتم إرسالها:", {
            title: noteTitle,
            content: noteContent,
            is_important: isImportant,
            patient_id: {{ $patient->id }}
        });

        // إنشاء كائن FormData
        const formData = new FormData();
        formData.append('title', noteTitle);
        formData.append('content', noteContent);
        formData.append('is_important', isImportant ? '1' : '0');
        formData.append('patient_id', {{ $patient->id }});
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // إرسال البيانات باستخدام AJAX
        fetch('{{ route("patient-notes.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("استجابة الخادم:", response);
            if (!response.ok) {
                throw new Error('خطأ في الاستجابة: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("بيانات الاستجابة:", data);
            if (data.success) {
                // إغلاق المودال
                document.getElementById('addNoteModal').classList.remove('active');

                // تحديث الصفحة لعرض الملاحظة الجديدة
                window.location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء حفظ الملاحظة');
            }
        })
        .catch(error => {
            console.error('خطأ في إضافة الملاحظة:', error);
            alert('حدث خطأ أثناء إضافة الملاحظة. يرجى المحاولة مرة أخرى.');
        });
    });

    // تفعيل مودال إضافة موعد
    const appointmentModal = document.getElementById('addAppointmentModal');
    const openAppointmentModalBtn = document.getElementById('openAddAppointmentModal');
    const closeAppointmentModalBtn = document.getElementById('closeAddAppointmentModal');
    const addAppointmentForm = document.getElementById('addAppointmentForm');

    // فتح المودال
    openAppointmentModalBtn.addEventListener('click', function(e) {
        e.preventDefault();
        appointmentModal.classList.add('active');

        // تهيئة التقويم عند فتح المودال
        const calendarDays = document.getElementById('calendarDays');
        if (calendarDays && calendarDays.children.length === 0) {
            renderCalendar(new Date());
        }
    });

    // إغلاق المودال
    closeAppointmentModalBtn.addEventListener('click', function() {
        appointmentModal.classList.remove('active');
    });

    // إغلاق المودال عند النقر خارجه
    appointmentModal.addEventListener('click', function(e) {
        if (e.target === appointmentModal) {
            appointmentModal.classList.remove('active');
        }
    });

    // دالة لعرض التقويم
    function renderCalendar(date) {
        const calendarDays = document.getElementById('calendarDays');
        const currentMonthElement = document.getElementById('currentMonth');

        // مسح التقويم الحالي
        calendarDays.innerHTML = '';

        // تحديث عنوان الشهر
        const year = date.getFullYear();
        const month = date.getMonth();

        // أسماء الأشهر
        const monthNames = ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        currentMonthElement.textContent = `${monthNames[month]} ${year}`;

        // اليوم الأول من الشهر
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        // اليوم الأول في الأسبوع (0 = الأحد، 1 = الاثنين، ...)
        const firstDayOfWeek = firstDay.getDay();

        // عدد الأيام في الشهر
        const daysInMonth = lastDay.getDate();

        // تاريخ اليوم
        const today = new Date();

        // إضافة الأيام السابقة من الشهر السابق
        const prevMonth = new Date(year, month, 0);
        const daysInPrevMonth = prevMonth.getDate();

        for (let i = firstDayOfWeek - 1; i >= 0; i--) {
            const dayElement = document.createElement('div');
            const dayNumber = daysInPrevMonth - i;
            dayElement.className = 'calendar-day other-month';
            dayElement.textContent = dayNumber;

            // تنسيق التاريخ بالشكل المطلوب (YYYY-MM-DD)
            const prevMonthDate = new Date(year, month - 1, dayNumber);
            const formattedDate = `${prevMonthDate.getFullYear()}-${(prevMonthDate.getMonth() + 1).toString().padStart(2, '0')}-${dayNumber.toString().padStart(2, '0')}`;
            dayElement.setAttribute('data-date', formattedDate);

            dayElement.addEventListener('click', () => selectCalendarDay(dayElement));
            calendarDays.appendChild(dayElement);
        }

        // إضافة أيام الشهر الحالي
        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = i;

            // تحديد اليوم الحالي
            if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
                dayElement.classList.add('today');
            }

            // تنسيق التاريخ بالشكل المطلوب (YYYY-MM-DD)
            const formattedDate = `${year}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            dayElement.setAttribute('data-date', formattedDate);

            dayElement.addEventListener('click', () => selectCalendarDay(dayElement));
            calendarDays.appendChild(dayElement);
        }

        // حساب عدد الخلايا المتبقية لإكمال الجدول (عادة 42 خلية للجدول 6×7)
        const remainingDays = 42 - (firstDayOfWeek + daysInMonth);

        // إضافة الأيام من الشهر التالي
        for (let i = 1; i <= remainingDays; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day other-month';
            dayElement.textContent = i;

            // تنسيق التاريخ بالشكل المطلوب (YYYY-MM-DD)
            const nextMonthDate = new Date(year, month + 1, i);
            const formattedDate = `${nextMonthDate.getFullYear()}-${(nextMonthDate.getMonth() + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            dayElement.setAttribute('data-date', formattedDate);

            dayElement.addEventListener('click', () => selectCalendarDay(dayElement));
            calendarDays.appendChild(dayElement);
        }
    }

    // تفعيل أحداث مودال إضافة موعد جديد عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة طبقة الخلفية السوداء إذا لم تكن موجودة
        if (!document.getElementById('popupBackdrop')) {
            const backdrop = document.createElement('div');
            backdrop.id = 'popupBackdrop';
            backdrop.className = 'popup-backdrop';
            document.body.appendChild(backdrop);
        }

        const popupBackdrop = document.getElementById('popupBackdrop');

        // تفعيل زر فتح المودال
        const openAddAppointmentModalBtn = document.getElementById('openAddAppointmentModal');
        if (openAddAppointmentModalBtn) {
            openAddAppointmentModalBtn.addEventListener('click', function() {
                // إعادة تعيين النموذج
                document.getElementById('addAppointmentForm').reset();
                // فتح المودال
                document.getElementById('addAppointmentModal').classList.add('active');
            });
        }

        // تفعيل زر إغلاق المودال
        const closeAddAppointmentModalBtn = document.getElementById('closeAddAppointmentModal');
        if (closeAddAppointmentModalBtn) {
            closeAddAppointmentModalBtn.addEventListener('click', function() {
                document.getElementById('addAppointmentModal').classList.remove('active');
            });
        }

        // تفعيل زر فتح التقويم
        const datePickerBtn = document.getElementById('datePickerBtn');
        const datePickerPopup = document.getElementById('datePickerPopup');

        if (datePickerBtn && datePickerPopup) {
            datePickerBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث

                // إغلاق القوائم الأخرى
                document.getElementById('timePickerPopup').classList.remove('active');
                document.getElementById('sessionTypePopup').classList.remove('active');

                // فتح التقويم والخلفية
                datePickerPopup.classList.add('active');
                popupBackdrop.classList.add('active');

                // إنشاء التقويم إذا لم يكن موجودًا
                if (document.getElementById('calendarDays').children.length === 0) {
                    renderCalendar(new Date());
                }
            });
        }

        // تفعيل زر فتح قائمة الأوقات
        const timePickerBtn = document.getElementById('timePickerBtn');
        const timePickerPopup = document.getElementById('timePickerPopup');

        if (timePickerBtn && timePickerPopup) {
            timePickerBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث

                // التحقق من اختيار التاريخ أولًا
                const appointmentDateInput = document.getElementById('appointmentDate');
                if (!appointmentDateInput.value) {
                    alert('يرجى اختيار التاريخ أولًا');
                    return;
                }

                // إغلاق القوائم الأخرى
                document.getElementById('datePickerPopup').classList.remove('active');
                document.getElementById('sessionTypePopup').classList.remove('active');

                // فتح قائمة الأوقات
                timePickerPopup.classList.add('active');
                popupBackdrop.classList.add('active');

                // جلب الأوقات المتاحة
                fetchAvailableTimes(appointmentDateInput.value);
            });
        }

        // تفعيل زر فتح قائمة أنواع الجلسات
        const sessionTypeBtn = document.getElementById('sessionTypeBtn');
        const sessionTypePopup = document.getElementById('sessionTypePopup');

        if (sessionTypeBtn && sessionTypePopup) {
            sessionTypeBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث

                // إغلاق القوائم الأخرى
                document.getElementById('datePickerPopup').classList.remove('active');
                document.getElementById('timePickerPopup').classList.remove('active');

                // فتح قائمة أنواع الجلسات
                sessionTypePopup.classList.add('active');
                popupBackdrop.classList.add('active');
            });
        }

        // تفعيل اختيار الوقت من القائمة
        const timeSlots = document.querySelectorAll('.time-slot');
        timeSlots.forEach(timeSlot => {
            timeSlot.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث
                selectTimeSlot(this.getAttribute('data-time') || '', this.textContent.trim());
            });
        });

        // تفعيل اختيار نوع الجلسة من القائمة
        const sessionTypes = document.querySelectorAll('.session-type');
        sessionTypes.forEach(sessionType => {
            sessionType.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث
                selectSessionType(this.textContent.trim());
            });
        });

        // إضافة أحداث للنقر على عناصر القوائم المنسدلة لمنع انتشار الحدث
        document.querySelectorAll('.date-picker-popup, .time-picker-popup, .session-type-popup').forEach(popup => {
            popup.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث
            });
        });

        // تفعيل المناطق القابلة للنقر في التقويم
        const calendarDays = document.getElementById('calendarDays');
        if (calendarDays) {
            // منع انتشار الأحداث لعناصر التقويم
            calendarDays.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // إغلاق القوائم المنسدلة عند النقر على الخلفية
        if (popupBackdrop) {
            popupBackdrop.addEventListener('click', function() {
                // إغلاق جميع القوائم المنسدلة
                document.getElementById('datePickerPopup').classList.remove('active');
                document.getElementById('timePickerPopup').classList.remove('active');
                document.getElementById('sessionTypePopup').classList.remove('active');
                popupBackdrop.classList.remove('active');
            });
        }

        // أحداث التنقل بين الأشهر في التقويم
        const prevMonthBtn = document.querySelector('.prev-month');
        const nextMonthBtn = document.querySelector('.next-month');

        if (prevMonthBtn && nextMonthBtn) {
            // حفظ التاريخ الحالي في متغير عام
            window.currentCalendarDate = new Date();

            prevMonthBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث
                window.currentCalendarDate.setMonth(window.currentCalendarDate.getMonth() - 1);
                renderCalendar(window.currentCalendarDate);
            });

            nextMonthBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // منع انتشار الحدث
                window.currentCalendarDate.setMonth(window.currentCalendarDate.getMonth() + 1);
                renderCalendar(window.currentCalendarDate);
            });
        }
    });

    // تحسين دالة selectTimeSlot
    function selectTimeSlot(time, formattedTime) {
        // تحديث قيمة حقل الوقت
        const appointmentTimeInput = document.getElementById('appointmentTime');
        appointmentTimeInput.value = formattedTime;
        appointmentTimeInput.setAttribute('data-raw-time', time);

        // إغلاق قائمة الأوقات
        document.getElementById('timePickerPopup').classList.remove('active');
        document.getElementById('popupBackdrop').classList.remove('active');
    }

    // تحسين دالة selectSessionType
    function selectSessionType(type) {
        // تحديث قيمة حقل نوع الجلسة
        const appointmentTypeInput = document.getElementById('appointmentType');
        appointmentTypeInput.value = type;

        // إغلاق قائمة أنواع الجلسات
        document.getElementById('sessionTypePopup').classList.remove('active');
        document.getElementById('popupBackdrop').classList.remove('active');
    }

    // دالة لتفعيل اختيار اليوم من التقويم
    function selectCalendarDay(day) {
        event.stopPropagation(); // منع انتشار الحدث

        // الحصول على عناصر التقويم
        const calendarDays = document.querySelectorAll('.calendar-day');
        const appointmentDateInput = document.getElementById('appointmentDate');
        const datePickerPopup = document.getElementById('datePickerPopup');
        const popupBackdrop = document.getElementById('popupBackdrop');

        // إزالة الفئة "selected" من جميع الأيام
        calendarDays.forEach(dayElement => {
            dayElement.classList.remove('selected');
        });

        // إضافة الفئة "selected" لليوم المحدد
        day.classList.add('selected');

        // تحديث قيمة حقل التاريخ
        const selectedDate = day.getAttribute('data-date');
        appointmentDateInput.value = selectedDate;

        // إغلاق التقويم
        datePickerPopup.classList.remove('active');
        popupBackdrop.classList.remove('active');
    }

    // دالة لجلب الأوقات المتاحة من الخادم
    function fetchAvailableTimes(date) {
        // الحصول على معرف العيادة من بيانات المريض
        const clinicId = {{ $patient->dental_clinic_id ?? 1 }}; // القيمة الافتراضية هي 1 إذا لم يكن للمريض عيادة

        // عرض رسالة تحميل في popup الأوقات
        const timeSlots = document.querySelector('.time-slots');
        timeSlots.innerHTML = '<div class="loading-times">جاري تحميل الأوقات المتاحة...</div>';

        // استدعاء API للحصول على الأوقات المتاحة
        fetch(`/appointments/available-times?date=${date}&clinic_id=${clinicId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`خطأ في الاستجابة: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('الأوقات المستلمة:', data); // سجل الاستجابة للتصحيح

                if (data.length > 0) {
                    // عرض الأوقات المتاحة
                    timeSlots.innerHTML = '';
                    data.forEach(timeSlot => {
                        const timeElement = document.createElement('div');
                        timeElement.className = `time-slot ${!timeSlot.available ? 'unavailable' : ''}`;
                        timeElement.textContent = timeSlot.formatted;

                        if (timeSlot.available) {
                            timeElement.addEventListener('click', () => selectTimeSlot(timeSlot.time, timeSlot.formatted));
                        } else {
                            timeElement.title = 'هذا الوقت محجوز';
                        }

                        timeSlots.appendChild(timeElement);
                    });
                } else {
                    // للتسهيل، يمكننا عرض بعض الأوقات الافتراضية إذا لم تكن هناك بيانات
                    timeSlots.innerHTML = '';

                    // أوقات افتراضية للعرض
                    const defaultTimes = [
                        { time: '09:00', formatted: '9:00 AM', available: true },
                        { time: '09:30', formatted: '9:30 AM', available: true },
                        { time: '10:00', formatted: '10:00 AM', available: true },
                        { time: '10:30', formatted: '10:30 AM', available: true },
                        { time: '11:00', formatted: '11:00 AM', available: true },
                        { time: '11:30', formatted: '11:30 AM', available: true },
                        { time: '12:00', formatted: '12:00 PM', available: true },
                        { time: '12:30', formatted: '12:30 PM', available: true },
                        { time: '13:00', formatted: '1:00 PM', available: true },
                        { time: '13:30', formatted: '1:30 PM', available: true },
                        { time: '14:00', formatted: '2:00 PM', available: true },
                        { time: '14:30', formatted: '2:30 PM', available: true }
                    ];

                    defaultTimes.forEach(timeSlot => {
                        const timeElement = document.createElement('div');
                        timeElement.className = 'time-slot';
                        timeElement.textContent = timeSlot.formatted;
                        timeElement.addEventListener('click', () => selectTimeSlot(timeSlot.time, timeSlot.formatted));
                        timeSlots.appendChild(timeElement);
                    });
                }
            })
            .catch(error => {
                console.error('خطأ في جلب الأوقات المتاحة:', error);

                // عرض أوقات افتراضية في حالة الخطأ
                timeSlots.innerHTML = '';

                // أوقات افتراضية للعرض في حالة الخطأ
                const defaultTimes = [
                    '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM',
                    '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM',
                    '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM'
                ];

                defaultTimes.forEach(time => {
                    const timeElement = document.createElement('div');
                    timeElement.className = 'time-slot';
                    timeElement.textContent = time;
                    timeElement.addEventListener('click', () => selectTimeSlot(time, time));
                    timeSlots.appendChild(timeElement);
                });
            });
    }

    // دالة محسنة لتبديل تمييز الموعد بنجمة
    function toggleStarAppointment(appointmentId, button) {
        // التحقق من وجود رمز CSRF
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (!metaToken) {
            console.error('رمز CSRF غير موجود!');
            return;
        }

        const token = metaToken.getAttribute('content');

        // إضافة الرمز في محتوى الطلب
        fetch(`/appointments/${appointmentId}/toggle-star`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _token: token }) // إضافة الرمز في محتوى الطلب أيضًا
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('خطأ في استجابة الخادم: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('استجابة تمييز النجمة:', data); // سجل الاستجابة للتصحيح

            if (data.success) {
                // تبديل حالة أيقونة النجمة
                const starIcon = button.querySelector('i');
                if (data.is_starred) {
                    starIcon.className = '';
                    starIcon.classList.add('fas', 'fa-star');
                } else {
                    starIcon.className = '';
                    starIcon.classList.add('far', 'fa-star');
                }

                // عرض رسالة نجاح (اختياري)
                if (data.message) {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('خطأ في تبديل حالة النجمة:', error);
            alert('حدث خطأ أثناء تمييز الموعد. يرجى المحاولة مرة أخرى.');
        });
    }

    // دالة لإرسال نموذج النجمة بطريقة AJAX
    function submitStarForm(button) {
        const form = button.closest('form');
        const url = form.action;
        const token = form.querySelector('input[name="_token"]').value;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _token: token })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('خطأ في استجابة الخادم: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // تبديل حالة أيقونة النجمة
                const starIcon = button.querySelector('i');
                if (data.is_starred) {
                    starIcon.className = '';
                    starIcon.classList.add('fas', 'fa-star');
                } else {
                    starIcon.className = '';
                    starIcon.classList.add('far', 'fa-star');
                }
            }
        })
        .catch(error => {
            console.error('خطأ في تبديل حالة النجمة:', error);
        });

        return false; // منع إرسال النموذج بالطريقة التقليدية
    }

    // دالة لتحرير تفاصيل الموعد
    function editAppointment(appointmentId) {
        event.preventDefault(); // منع الانتقال الافتراضي

        // عرض رسالة تحميل
        console.log(`تحرير الموعد رقم ${appointmentId}`);

        // إرسال طلب AJAX للحصول على بيانات الموعد
        fetch(`/appointments/${appointmentId}/edit`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`خطأ في الاستجابة: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // فتح مودال التحرير وملء البيانات
                openEditAppointmentModal(data.appointment);
            } else {
                alert('حدث خطأ أثناء تحميل بيانات الموعد');
            }
        })
        .catch(error => {
            console.error('خطأ في تحميل بيانات الموعد:', error);
            alert('حدث خطأ أثناء تحميل بيانات الموعد. يرجى المحاولة مرة أخرى.');
        });

        return false; // منع الانتقال الافتراضي (للتأكيد)
    }

    // دالة لفتح مودال تحرير الموعد وملء البيانات
    function openEditAppointmentModal(appointment) {
        // إذا كان المودال غير موجود، قم بإنشائه
        let editModal = document.getElementById('editAppointmentModal');
        if (!editModal) {
            // إنشاء المودال إذا لم يكن موجودًا
            editModal = document.createElement('div');
            editModal.id = 'editAppointmentModal';
            editModal.className = 'modal-overlay';
            editModal.innerHTML = `
                <div class="modal-container appointment-modal">
                    <div class="modal-content appointment-content">
                        <div class="modal-header appointment-header">
                            <h3>تعديل الموعد</h3>
                        </div>
                        <div class="modal-body">
                            <form id="editAppointmentForm">
                                <input type="hidden" id="editAppointmentId">
                                <div class="appointment-form-grid">
                                    <!-- حقول النموذج ستضاف هنا -->
                                </div>
                                <div class="modal-actions">
                                    <button type="button" class="btn btn-cancel" id="closeEditAppointmentModal">الغاء</button>
                                    <button type="submit" class="btn btn-save">حفظ التغييرات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(editModal);

            // إضافة حدث النقر لزر الإغلاق
            document.getElementById('closeEditAppointmentModal').addEventListener('click', function() {
                editModal.classList.remove('active');
            });
        }

        // ملء البيانات في المودال
        document.getElementById('editAppointmentId').value = appointment.id;

        // عرض المودال
        editModal.classList.add('active');
    }

    // تفعيل مودال إضافة ملاحظة جديدة
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل زر فتح مودال إضافة ملاحظة
        const addNoteBtn = document.querySelector('.add-note-btn');
        if (addNoteBtn) {
            addNoteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // عرض تاريخ اليوم في حقل التاريخ
                const currentDate = new Date();
                const formattedDate = `${currentDate.getDate()}/${currentDate.getMonth() + 1}/${currentDate.getFullYear()}`;
                document.getElementById('currentDate').textContent = formattedDate;

                // فتح المودال
                document.getElementById('addNoteModal').classList.add('active');
            });
        }

        // تفعيل زر إغلاق مودال إضافة ملاحظة
        const closeAddNoteModalBtn = document.getElementById('closeAddNoteModal');
        if (closeAddNoteModalBtn) {
            closeAddNoteModalBtn.addEventListener('click', function() {
                document.getElementById('addNoteModal').classList.remove('active');
            });
        }

        // معالجة نموذج إضافة ملاحظة
        const addNoteForm = document.getElementById('addNoteForm');
        if (addNoteForm) {
            addNoteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // جمع بيانات النموذج
                const noteTitle = document.getElementById('noteTitle').value;
                const noteContent = document.getElementById('noteContent').value;

                // التحقق من وجود عنوان
                if (!noteTitle) {
                    alert('يرجى إدخال عنوان للملاحظة');
                    return;
                }

                // إنشاء كائن FormData
                const formData = new FormData();
                formData.append('title', noteTitle);
                formData.append('content', noteContent);
                formData.append('patient_id', {{ $patient->id }});
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // إرسال البيانات باستخدام AJAX
                fetch('{{ route("patient-notes.store") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('خطأ في الاستجابة: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // إغلاق المودال
                        document.getElementById('addNoteModal').classList.remove('active');

                        // تحديث الصفحة لعرض الملاحظة الجديدة
                        window.location.reload();
                    } else {
                        alert(data.message || 'حدث خطأ أثناء حفظ الملاحظة');
                    }
                })
                .catch(error => {
                    console.error('خطأ في إضافة الملاحظة:', error);
                    alert('حدث خطأ أثناء إضافة الملاحظة. يرجى المحاولة مرة أخرى.');
                });
            });
        }
    });
</script>
@endsection

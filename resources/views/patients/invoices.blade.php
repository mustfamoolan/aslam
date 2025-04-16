@extends('layouts.patient')

@section('title', 'فواتير المريض')

@section('styles')
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

    /* تنسيقات جدول الفواتير */
    .invoices-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin: 0 20px 30px;
        overflow: hidden;
        width: 800px; /* ارتفاع يتناسب مع السايدبار */
        height: 1000px; /* ارتفاع يتناسب مع السايدبار */
        display: flex;
        flex-direction: column;
    }

    .invoices-header {
        background-color: #f8f8f8;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
        flex-shrink: 0;
    }

    .invoices-header h2 {
        color: #3370A6;
        font-size: 18px;
        margin: 0;
        font-weight: bold;
    }

    .table-container {
        flex: 1;
        overflow: auto;
    }

    .invoices-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoices-table th {
        background-color: #f8f8f8;
        color: #3370A6;
        font-weight: bold;
        text-align: right;
        padding: 12px 20px;
        border-bottom: 1px solid #eaeaea;
    }

    .invoices-table td {
        padding: 12px 20px;
        border-bottom: 1px solid #eaeaea;
        color: #555;
    }

    .invoices-table tr:hover {
        background-color: #f9f9f9;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        text-align: center;
        min-width: 100px;
    }

    .status-unpaid {
        background-color: #FF5959;
        color: white;
    }

    .status-paid {
        background-color: #2AD062;
        color: white;
    }

    .status-partial {
        background-color: #FFC107;
        color: white;
    }

    .add-session-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        border: 2px dashed #ccc;
        color: #38A3A5;
        margin: 0;
        border-radius: 0 0 10px 10px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.2s;
    }

    .add-session-button:hover {
        background-color: #f9f9f9;
        border-color: #38A3A5;
    }

    .add-session-button i {
        margin-left: 8px;
    }

    /* تنسيقات قسم الإحصائيات */
    .stats-container {
        width: 520px;
        position: absolute;
        left: 40px;
        top: 85px;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 30px;
        padding: 36px 44px;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 40px rgba(100, 180, 255, 0.15);
        transition: all 0.3s;
        position: relative;
        overflow: visible;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        z-index: 0;
        border-radius: 30px;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 45px rgba(100, 180, 255, 0.2);
    }

    .stat-info {
        text-align: right;
        position: relative;
        z-index: 1;
        margin-left: 100px;
    }

    .stat-title {
        color: #4A8CFF;
        font-size: 36px;
        margin-bottom: 5px;
        font-weight: bold;
        letter-spacing: -0.5px;
    }

    .stat-subtitle {
        color: #8CB4EC;
        font-size: 24px;
    }

    .stat-circle {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        font-weight: bold;
        color: white;
        position: absolute;
        left: -50px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.2);
    }

    /* ألوان مختلفة لكل بطاقة */
    .circle-blue {
        background: linear-gradient(135deg, #5B9BFF, #1E5BBF);
    }

    .circle-teal {
        background: linear-gradient(135deg, #27A5B8, #0F5E6A);
    }

    .circle-cyan {
        background: linear-gradient(135deg, #22D8EA, #0099B0);
    }

    .circle-red {
        background: linear-gradient(135deg, #FF6969, #C4353B);
    }

    /* تخصيص لون النص لكل بطاقة */
    .stat-card:nth-child(1) .stat-title {
        color: #4A8CFF;
    }

    .stat-card:nth-child(2) .stat-title {
        color: #27A5B8;
    }

    .stat-card:nth-child(3) .stat-title {
        color: #22D8EA;
    }

    .stat-card:nth-child(4) .stat-title {
        color: #FF6969;
    }

    /* تأثير ظل التوهج لكل بطاقة */
    .stat-card:nth-child(1) {
        box-shadow: 0 10px 25px rgba(74, 140, 255, 0.15);
    }

    .stat-card:nth-child(2) {
        box-shadow: 0 10px 25px rgba(39, 165, 184, 0.15);
    }

    .stat-card:nth-child(3) {
        box-shadow: 0 10px 25px rgba(34, 216, 234, 0.15);
    }

    .stat-card:nth-child(4) {
        box-shadow: 0 10px 25px rgba(255, 105, 105, 0.15);
    }

    /* تخطيط الشاشة */
    .content-layout {
        display: flex;
        gap: 0;
        position: relative;
    }

    .main-content {
        flex: 1;
    }

    /* إضافة تنسيقات متجاوبة لمختلف أحجام الشاشات */
    @media screen and (max-width: 1600px) {
        .content-layout {
            flex-direction: column;
        }

        .stats-container {
            width: 100%;
            position: static;
            padding: 0 20px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .stat-card {
            width: calc(50% - 15px);
            margin-bottom: 30px;
        }

        .invoices-section {
            width: calc(100% - 40px);
        }
    }

    @media screen and (max-width: 1200px) {
        .stat-card {
            padding: 25px 35px;
        }

        .stat-circle {
            width: 140px;
            height: 140px;
            font-size: 48px;
            left: -35px;
        }

        .stat-title {
            font-size: 28px;
        }

        .stat-subtitle {
            font-size: 18px;
        }

        .stat-info {
            margin-left: 80px;
        }
    }

    @media screen and (max-width: 992px) {
        .stats-container {
            padding: 0 10px;
        }

        .stat-card {
            width: 100%;
            margin-bottom: 20px;
        }

        .invoices-section {
            height: auto;
            min-height: 600px;
        }

        .search-container {
            padding: 0 10px;
        }
    }

    @media screen and (max-width: 768px) {
        .stat-card {
            padding: 20px 25px;
        }

        .stat-circle {
            width: 120px;
            height: 120px;
            font-size: 36px;
            left: -25px;
        }

        .stat-title {
            font-size: 22px;
        }

        .stat-subtitle {
            font-size: 14px;
        }

        .stat-info {
            margin-left: 70px;
        }

        .invoices-table th,
        .invoices-table td {
            padding: 10px 12px;
            font-size: 14px;
        }

        .status-badge {
            min-width: 80px;
            font-size: 11px;
            padding: 4px 8px;
        }
    }

    @media screen and (max-width: 576px) {
        .invoices-section {
            margin: 0 10px 20px;
            width: calc(100% - 20px);
        }

        .invoices-table {
            display: block;
            overflow-x: auto;
        }

        .invoices-table th:nth-child(3),
        .invoices-table td:nth-child(3) {
            display: none;
        }

        .invoices-header h2 {
            font-size: 16px;
        }

        .stat-card {
            padding: 15px 20px;
        }

        .stat-circle {
            width: 90px;
            height: 90px;
            font-size: 28px;
            left: -15px;
        }

        .stat-title {
            font-size: 18px;
        }

        .stat-subtitle {
            font-size: 12px;
        }

        .stat-info {
            margin-left: 50px;
        }

        .add-session-button {
            padding: 12px;
            font-size: 14px;
        }
    }

    /* تنسيقات المودال */
    .modal {
        z-index: 9999 !important; /* التأكد أن المودال يظهر فوق كل العناصر الأخرى */
    }

    .modal-backdrop {
        z-index: 9990 !important; /* التأكد أن خلفية المودال تظهر بشكل صحيح */
    }

    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    .modal-dialog {
        margin-top: 60px;
    }

    .modal-header {
        background-color: #f8f8f8;
        border-bottom: 1px solid #eaeaea;
        padding: 15px 20px;
        position: relative;
    }

    .modal-title {
        color: #3370A6;
        font-weight: bold;
        margin: 0;
        text-align: right;
        width: 100%;
    }

    .close {
        position: absolute;
        left: 15px;
        top: 15px;
        color: #666;
        font-size: 24px;
        opacity: 0.7;
        padding: 0;
        margin: 0;
        background: none;
        border: none;
    }

    .close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #eaeaea;
        background-color: #f8f8f8;
        padding: 15px 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: bold;
        text-align: right;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        direction: rtl;
        text-align: right;
    }

    .input-group-text {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        color: #666;
    }

    /* زر حفظ الفاتورة */
    .btn-primary {
        background-color: #38A3A5;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2C8385;
    }

    .btn-secondary {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        color: #666;
        padding: 10px 25px;
        border-radius: 5px;
    }

    .btn-secondary:hover {
        background-color: #e5e5e5;
    }

    /* تصحيح تعديلات التخطيط السابقة */
    .content-layout {
        position: relative;
        z-index: 1; /* تأكد من أن العناصر الأخرى لها z-index أقل من المودال */
    }

    /* تصحيح overflow */
    .content-area {
        overflow: visible !important;
    }

    /* تصحيح تنسيقات الصف في المودال */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-md-6 {
        position: relative;
        width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* تنسيقات قسم إضافة الفاتورة */
    .add-invoice-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        margin: 30px 20px;
        overflow: hidden;
        width: calc(100% - 40px);
        max-width: 1200px;
        position: relative;
    }

    .section-header {
        background-color: #f8f8f8;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
    }

    .section-header h3 {
        color: #3370A6;
        font-size: 20px;
        margin: 0;
        font-weight: bold;
    }

    .form-rows {
        padding: 25px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        gap: 20px;
    }

    .form-group {
        flex: 1;
        min-width: 250px;
    }

    .form-group.full-width {
        width: 100%;
        flex-basis: 100%;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        direction: rtl;
        text-align: right;
    }

    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        flex: 1;
    }

    .input-group-append {
        display: flex;
        align-items: stretch;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0 15px;
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 6px 0 0 6px;
        color: #555;
    }

    .form-actions {
        padding: 15px 25px 25px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-primary {
        background-color: #38A3A5;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background-color: #2C8385;
    }

    /* تعديل الـ layout ليناسب وجود قسم إضافة الفاتورة */
    .content-layout {
        flex-direction: column;
    }

    @media screen and (max-width: 1600px) {
        .stats-container {
            margin-bottom: 60px;
        }
    }

    /* تنسيقات المودال المخصص */
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        z-index: 9999;
        direction: rtl;
    }

    .custom-modal.active {
        display: block;
    }

    .custom-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-modal-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        width: 90%;
        max-width: 800px;
        border-radius: 10px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .custom-modal-header {
        background-color: #f8f8f8;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-modal-header h3 {
        margin: 0;
        color: #3370A6;
        font-weight: bold;
    }

    .close-modal-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    .custom-modal-body {
        padding: 20px;
        overflow-y: auto;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.half-width {
        flex: 1;
        min-width: 250px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        border: none;
    }

    .btn-primary {
        background-color: #38A3A5;
        color: white;
    }

    .btn-secondary {
        background-color: #f5f5f5;
        color: #666;
        border: 1px solid #ddd;
    }

    .input-group {
        display: flex;
    }

    .input-group-append {
        display: flex;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0 15px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        color: #666;
    }
</style>
@endsection

@section('content')
<!-- بنية الصفحة -->
<div class="content-layout">
    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <!-- مربع البحث -->
        <div class="search-container">
            <div class="search-bar">
                <input type="text" placeholder="ابحث عن فاتورة..." id="invoiceSearch">
                <button type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- قسم قائمة الفواتير -->
        <div class="invoices-section">
            <div class="invoices-header">
                <h2>آخـر الجلسات</h2>
            </div>

            <div class="table-container">
                <table class="invoices-table">
                    <thead>
                        <tr>
                            <th>ت</th>
                            <th>عنوان الفاتورة</th>
                            <th>الجلسات المتضمنة</th>
                            <th>تاريخ الدفع</th>
                            <th>المبلغ الكلي</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->invoices as $index => $invoice)
                        <tr>
                            <td><input type="checkbox" name="invoice_id[]" value="{{ $invoice->id }}"></td>
                            <td>{{ $invoice->session_title }}</td>
                            <td>{{ $invoice->invoice_type }}</td>
                            <td>{{ $invoice->issue_date->format('d / m / Y') }}</td>
                            <td>{{ number_format($invoice->amount) }}</td>
                            <td>
                                @if($invoice->is_paid)
                                    <span class="status-badge status-paid">مـدفــوع</span>
                                @elseif($invoice->paid_amount > 0)
                                    <span class="status-badge status-partial">تم دفع {{ round(($invoice->paid_amount / $invoice->amount) * 100) }}%</span>
                                @else
                                    <span class="status-badge status-unpaid">لم يتم الدفع</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد فواتير لعرضها</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- إعادة إضافة زر إنشاء فاتورة جديدة في أسفل الجدول -->
            <a href="#" class="add-session-button" id="addInvoiceBtn">
                <i class="fas fa-plus-circle"></i>
                إضافة فاتورة جديدة
            </a>
        </div>
    </div>

    <!-- قسم الإحصائيات -->
    <div class="stats-container">
        <!-- بطاقة عدد الفواتير -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-title">عدد الفواتير</div>
                <div class="stat-subtitle">الكلي</div>
            </div>
            <div class="stat-circle circle-blue">{{ $totalInvoices }}</div>
        </div>

        <!-- بطاقة المبلغ الكلي -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-title">المبلـغ الكُلي</div>
                <div class="stat-subtitle">لكافة الفواتير</div>
            </div>
            <div class="stat-circle circle-teal">{{ number_format($totalAmount/1000) }}</div>
        </div>

        <!-- بطاقة المبلغ المدفوع -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-title">المبلـغ المدفوع</div>
                <div class="stat-subtitle">لكافة الفواتير</div>
            </div>
            <div class="stat-circle circle-cyan">{{ number_format($paidAmount/1000) }}</div>
        </div>

        <!-- بطاقة المبلغ المتبقي -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-title">المبلـغ المتبقي</div>
                <div class="stat-subtitle">لكافة الفواتير</div>
            </div>
            <div class="stat-circle circle-red">{{ number_format($remainingAmount/1000) }}</div>
        </div>
    </div>
</div>

<!-- مودال مخصص لإضافة فاتورة -->
<div id="customModal" class="custom-modal">
    <div class="custom-modal-overlay"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-header">
            <h3>إضافة فاتورة جديدة</h3>
            <button id="closeModalBtn" class="close-modal-btn">&times;</button>
        </div>
        <div class="custom-modal-body">
            <form id="invoiceForm" method="POST" action="{{ route('invoices.store') }}">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="session_title">عنوان الجلسة</label>
                        <input type="text" class="form-control" id="session_title" name="session_title" required>
                    </div>
                    <div class="form-group half-width">
                        <label for="invoice_type">نوع الفاتورة</label>
                        <select class="form-control" id="invoice_type" name="invoice_type" required>
                            <option value="">-- اختر نوع الفاتورة --</option>
                            <option value="حشوة جذر">حشوة جذر</option>
                            <option value="تنظيف أسنان">تنظيف أسنان</option>
                            <option value="تركيب طربوش">تركيب طربوش</option>
                            <option value="خلع سن">خلع سن</option>
                            <option value="تقويم أسنان">تقويم أسنان</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="issue_date">تاريخ الإصدار</label>
                        <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group half-width">
                        <label for="amount">المبلغ الكلي</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="amount" name="amount" min="0" step="1" required>
                            <div class="input-group-append">
                                <span class="input-group-text">د.ك</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half-width">
                        <label for="paid_amount">المبلغ المدفوع</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" min="0" step="1" required>
                            <div class="input-group-append">
                                <span class="input-group-text">د.ك</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group half-width">
                        <label for="remaining">المبلغ المتبقي</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="remaining" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text">د.ك</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="note">ملاحظات</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" id="cancelModalBtn" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الفاتورة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل مربع البحث
        const searchInput = document.getElementById('invoiceSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                console.log('بحث عن:', this.value);
                // منطق البحث في الجدول
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('.invoices-table tbody tr');

                tableRows.forEach(row => {
                    const invoiceTitle = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const paymentDate = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const amount = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

                    // بحث في عنوان الفاتورة وتاريخ الدفع والمبلغ والحالة
                    if (invoiceTitle.includes(searchTerm) ||
                        paymentDate.includes(searchTerm) ||
                        amount.includes(searchTerm) ||
                        status.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // حساب المبلغ المتبقي تلقائيا
        function updateRemainingAmount() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
            const remaining = amount - paidAmount;

            document.getElementById('remaining').value = remaining.toFixed(2);
        }

        // تفعيل مستمعي الأحداث للحقول الرقمية
        const amountInput = document.getElementById('amount');
        const paidAmountInput = document.getElementById('paid_amount');

        if (amountInput) {
            amountInput.addEventListener('input', updateRemainingAmount);
        }

        if (paidAmountInput) {
            paidAmountInput.addEventListener('input', updateRemainingAmount);
        }

        // معالجة تقديم النموذج عبر AJAX
        const invoiceForm = document.getElementById('invoiceForm');
        if (invoiceForm) {
            invoiceForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إغلاق المودال
                        closeModal();

                        // إظهار رسالة نجاح
                        alert('تم إضافة الفاتورة بنجاح');

                        // إعادة تحميل الصفحة لعرض الفاتورة الجديدة
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء إضافة الفاتورة');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إضافة الفاتورة');
                });
            });
        }

        // تفعيل المودال المخصص
        const modal = document.getElementById('customModal');
        const addInvoiceBtn = document.getElementById('addInvoiceBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const modalOverlay = document.querySelector('.custom-modal-overlay');

        // فتح المودال
        function openModal() {
            console.log('فتح المودال');
            if (invoiceForm) {
                invoiceForm.reset();
            }
            updateRemainingAmount();
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // منع التمرير في الصفحة الخلفية
            }
        }

        // إغلاق المودال
        function closeModal() {
            console.log('إغلاق المودال');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = ''; // استعادة التمرير
            }
        }

        // تفعيل زر فتح المودال
        if (addInvoiceBtn) {
            console.log('تم العثور على زر إضافة فاتورة');
            addInvoiceBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal();
            });
        } else {
            console.error('لم يتم العثور على زر إضافة فاتورة');
        }

        // تفعيل زر الإغلاق
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        // تفعيل زر الإلغاء
        if (cancelModalBtn) {
            cancelModalBtn.addEventListener('click', closeModal);
        }

        // إغلاق المودال عند النقر على الخلفية
        if (modalOverlay) {
            modalOverlay.addEventListener('click', closeModal);
        }

        // منع إغلاق المودال عند النقر داخل المحتوى
        const modalContainer = document.querySelector('.custom-modal-container');
        if (modalContainer) {
            modalContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
@endsection

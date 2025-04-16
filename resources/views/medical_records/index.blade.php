@extends('layouts.medical_record')

@section('title', 'السجل الطبي')

@section('content')
<!-- مربع البحث -->
<div class="search-container">
    <div class="search-bar">
        <input type="text" id="patient-search" placeholder="البحث عن مريض محدد">
        <button type="submit" id="search-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<!-- كارد جديد فوق زر إضافة مريض -->
<div class="sorting-card">
    <div class="sorting-card-title">ترتيب الجدول</div>
    <div class="sorting-card-buttons">
        <button class="sort-card-btn" data-sort="name">حسب الأبجدية</button>
        <button class="sort-card-btn" data-sort="latest">ابتداءً من آخر المرضى</button>
        <button class="sort-card-btn" data-sort="archived">المؤرشفة فقط</button>
        <button class="sort-card-btn" data-sort="starred">المميزة فقط</button>
        <button class="sort-card-btn" data-sort="diagnosis">حسب التشخيص</button>
    </div>
</div>

<!-- زر إضافة مريض جديد -->
<div class="add-patient-container">
    <button class="add-patient-btn" data-bs-toggle="modal" data-bs-target="#addPatientModal">
        <i class="fas fa-plus-circle"></i>
        إضافة مريض جديد
    </button>
</div>

<!-- مودال إضافة مريض جديد -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="add-patient-container">
                    <h2 class="add-patient-title">إضافة مريض جديد</h2>

                    <form id="addPatientForm">
                        @csrf
                        <div class="add-patient-content">
                            <div class="patient-form-row">
                                <div class="patient-form-col patient-info-col">
                                    <div class="patient-avatar-upload">
                                        <div class="avatar-placeholder">
                                            <img src="{{ asset('images/11.png') }}" alt="صورة المريض" id="patient-avatar-img">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>اسم المريض الثلاثي</label>
                                        <input type="text" class="form-control" name="full_name" placeholder="أكتب اسم المريض" required>
                                    </div>

                                    <div class="form-group">
                                        <label>العمر</label>
                                        <input type="number" class="form-control" name="age" placeholder="أكتب عمره" required>
                                    </div>

                                    <div class="form-group">
                                        <label>الجنس</label>
                                        <div class="gender-options">
                                            <div class="gender-option">
                                                <input type="radio" name="gender" id="male" value="male" checked>
                                                <label for="male">ذكر</label>
                                            </div>
                                            <div class="gender-option">
                                                <input type="radio" name="gender" id="female" value="female">
                                                <label for="female">أنثى</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>رقم الهاتف</label>
                                        <input type="text" class="form-control" name="phone_number" placeholder="أكتب رقم الهاتف" required>
                                    </div>

                                    <div class="form-group">
                                        <label>الوظيفة</label>
                                        <input type="text" class="form-control" name="occupation" placeholder="أكتب وظيفة المريض">
                                    </div>

                                    <div class="form-group">
                                        <label>محل السكن</label>
                                        <input type="text" class="form-control" name="address" placeholder="أكتب محل سكن المريض">
                                    </div>

                                    <div class="form-group">
                                        <label>الأمراض المزمنة</label>
                                        <input type="text" class="form-control" name="chronic_diseases" placeholder="أكتب كافة أمراض مريضك المزمنة (مفصولة بفواصل)">
                                    </div>

                                    <div class="form-group">
                                        <label>الحساسية</label>
                                        <input type="text" class="form-control" name="allergies" placeholder="أكتب أنواع الحساسية (مفصولة بفواصل)">
                                    </div>
                                </div>

                                <div class="patient-form-col patient-note-col">
                                    <div class="form-group note-group">
                                        <label>مُلاحظة</label>
                                        <textarea class="form-control" name="notes" placeholder="أكتب ملاحظة سريعة و هامة"></textarea>
                                    </div>

                                    <div class="patient-info-footer">
                                        <div class="patient-id">
                                            <span>تسلسل المريض:</span>
                                            <strong>{{ \App\Models\Patient::max('patient_number') + 1 }}</strong>
                                        </div>

                                        <div class="patient-date">
                                            <span>تاريخ الإضافة:</span>
                                            <strong>{{ now()->format('Y.m.d') }}</strong>
                                        </div>

                                        <div class="patient-time">
                                            <span>وقت الإضافة:</span>
                                            <strong>{{ now()->format('h:i A') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary add-patient-btn">إضافة المريض</button>
                            <button type="button" class="btn btn-secondary cancel-btn" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- قسم جدول السجلات الطبية -->
<div class="records-container">
    <div class="records-wrapper">
        <!-- جدول السجلات -->
        <div class="records-table-container">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>ت</th>
                        <th>الصورة</th>
                        <th>اســم المريــض</th>
                        <th>عمر المريض</th>
                        <th>تاريخ آخر زيارة</th>
                        <th>رقم السجل الطبي</th>
                        <th>إعــدادات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr>
                        <td><input type="checkbox" class="record-checkbox"></td>
                        <td><img src="{{ asset($patient->gender == 'female' ? 'images/22.png' : 'images/11.png') }}" alt="صورة المريض" class="patient-avatar"></td>
                        <td>{{ $patient->full_name }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>{{ $patient->registration_date->format('Y/m/d') }}</td>
                        <td>{{ $patient->patient_number }}</td>
                        <td class="actions-cell">
                            <button class="star-btn" data-id="{{ $patient->id }}" data-starred="{{ $patient->is_starred ? 'true' : 'false' }}">
                                <i class="fa{{ $patient->is_starred ? 's' : 'r' }} fa-star"></i>
                            </button>
                            <button class="archive-btn" data-id="{{ $patient->id }}" data-archived="{{ $patient->is_archived ? 'true' : 'false' }}">
                                @if($patient->is_archived)
                                    <i class="fas fa-archive"></i>
                                @else
                                    <i class="fas fa-archive" style="opacity: 0.5;"></i>
                                @endif
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* تعديل تصميم مربع البحث */
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

    /* تنسيق قسم السجلات */
    .records-container {
        width: 70%;
        margin-right: 20px;
        margin-bottom: 20px;
        padding: 0 20px;
    }

    .records-wrapper {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* تنسيق جدول السجلات */
    .records-table-container {
        padding: 0;
        overflow-x: auto;
    }

    .records-table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    .records-table th {
        background-color: #f0f7fa;
        padding: 15px 10px;
        text-align: center;
        font-weight: bold;
        color: #38A3A5;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
    }

    .records-table td {
        padding: 12px 10px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
        color: #555;
        font-size: 14px;
    }

    .records-table tr:nth-child(even) {
        background-color: #fafafa;
    }

    .records-table tr:hover {
        background-color: #f5f9fa;
    }

    .patient-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .actions-cell {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .star-btn, .archive-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .star-btn {
        color: #38A3A5;
    }

    .star-btn:hover {
        background-color: #e6f7f8;
    }

    .archive-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
        color: #38A3A5;
    }

    .archive-btn:hover {
        background-color: #e6f7f8;
    }

    /* تنسيق زر إضافة مريض جديد */
    .add-patient-container {
        display: flex;
        justify-content: center;
        padding: 0 20px;
        margin-bottom: 30px;
        width: 100%;
        margin-right: 0;
        position: relative;
        margin-top: 0;
    }

    .add-patient-btn {
        background-color: transparent;
        color: #38A3A5;
        border: 2px dashed #38A3A5;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
        position: absolute;
        left: 50px;
        top: 1100px;
    }

    .add-patient-btn:hover {
        background-color: #f0f9f9;
    }

    .add-patient-btn i {
        font-size: 18px;
    }

    /* تنسيق مودال إضافة مريض جديد */
    .modal-content .add-patient-container {
        background-color: #f2f7ff;
        border-radius: 10px;
        overflow: hidden;
        position: static;
        padding: 0;
        margin: 0;
        width: 100%;
        display: block;
    }

    .add-patient-title {
        background-color: #f2f7ff;
        color: #22577A;
        padding: 15px 20px;
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        font-family: 'Alexandria', sans-serif;
        border-bottom: 0px solid #d9e6f7;
    }

    .add-patient-content {
        padding: 20px;
    }

    .patient-form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .patient-form-col {
        flex: 1;
        min-width: 300px;
    }

    .patient-info-col {
        flex: 2;
    }

    .patient-note-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .patient-avatar-upload {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        overflow: hidden;
        border: 3px solid #3B6B8A;
    }

    .avatar-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-gender-text {
        color: #888;
        font-size: 12px;
        font-style: italic;
        font-family: 'Alexandria', sans-serif;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #22577A;
        font-size: 14px;
        text-align: right;
        font-family: 'Alexandria', sans-serif;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #d9e6f7;
        border-radius: 8px;
        background-color: white;
        color: #333;
        font-size: 14px;
        text-align: right;
        font-family: 'Alexandria', sans-serif;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .gender-options {
        display: flex;
        gap: 20px;
    }

    .gender-option {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .gender-option label {
        margin: 0;
        cursor: pointer;
    }

    .note-group {
        flex: 1;
    }

    .note-group textarea {
        height: 100%;
        min-height: 200px;
        resize: none;
    }

    .patient-info-footer {
        background-color: #e1ebfa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        border: 1px solid #d9e6f7;
    }

    .patient-id, .patient-date, .patient-time {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        font-family: 'Alexandria', sans-serif;
    }

    .patient-time {
        margin-bottom: 0;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
        gap: 15px;
        padding: 20px;
        border-top: 1px solid #d9e6f7;
        background-color: #f2f7ff;
    }

    .modal-footer .add-patient-btn,
    .modal-footer .btn-primary.add-patient-btn {
        background-color: #3B6B8A;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'Alexandria', sans-serif;
        position: static;
        display: inline-block;
    }

    .modal-footer .add-patient-btn:hover {
        background-color: #2c5a76;
    }

    .cancel-btn {
        background-color: white;
        color: #666;
        border: 1px solid #d9e6f7;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'Alexandria', sans-serif;
    }

    .cancel-btn:hover {
        background-color: #f8f9fa;
    }

    /* تنسيق checkbox */
    .record-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 3px;
        border: 1px solid #ccc;
        appearance: none;
        -webkit-appearance: none;
        position: relative;
        background-color: white;
    }

    .record-checkbox:checked {
        background-color: #38A3A5;
        border-color: #38A3A5;
    }

    .record-checkbox:checked:after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* تنسيق للشاشات الصغيرة */
    @media (max-width: 1200px) {
        .records-container, .add-patient-container {
            width: 80%;
        }
    }

    @media (max-width: 768px) {
        .records-container, .add-patient-container {
            width: 95%;
            margin-right: 10px;
        }
    }

    /* تنسيق كارد الترتيب الجديد */
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
        top: 200px; /* تعديل الموضع ليكون أعلى في الصفحة */
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
        color: #38A3A5;
    }

    .archived-row {
        opacity: 0.6;
        background-color: #f9f9f9 !important;
    }

    .archived-row:hover {
        opacity: 0.8;
    }

    /* تنسيق الإشعارات */
    .notification {
        position: fixed;
        top: 20px;
        left: 20px;
        padding: 12px 20px;
        border-radius: 4px;
        color: white;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        transform: translateY(-100px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .notification.success {
        background-color: #38A3A5;
    }

    .notification.error {
        background-color: #e74c3c;
    }

    .notification.warning {
        background-color: #f39c12;
    }

    .notification.info {
        background-color: #3498db;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تغيير صورة المريض حسب الجنس
        $('input[name="gender"]').on('change', function() {
            const gender = $(this).val();
            if (gender === 'female') {
                $('#patient-avatar-img').attr('src', "{{ asset('images/22.png') }}");
            } else {
                $('#patient-avatar-img').attr('src', "{{ asset('images/11.png') }}");
            }
        });

        // تفعيل أزرار الترتيب
        $('.sort-card-btn').on('click', function() {
            // إزالة الكلاس active من جميع الأزرار
            $('.sort-card-btn').removeClass('active');

            // إضافة الكلاس active للزر المضغوط
            $(this).addClass('active');

            // الحصول على نوع الترتيب
            const sortType = $(this).data('sort');

            // إرسال طلب AJAX لجلب البيانات المرتبة
            $.ajax({
                url: '{{ route("medical_records.sort") }}',
                type: 'GET',
                data: { sort: sortType },
                success: function(response) {
                    // تحديث جدول السجلات بالبيانات الجديدة
                    $('.records-table-container').html(response);
                },
                error: function(xhr) {
                    console.error('حدث خطأ أثناء جلب البيانات المرتبة');
                }
            });
        });

        // تفعيل البحث عند الضغط على زر البحث
        $('#search-btn').on('click', function(e) {
            e.preventDefault();
            performSearch();
        });

        // تفعيل البحث عند الضغط على Enter في مربع البحث
        $('#patient-search').on('keyup', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });

        // تفعيل البحث المباشر أثناء الكتابة (بعد توقف المستخدم عن الكتابة بـ 500 مللي ثانية)
        let searchTimeout;
        $('#patient-search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch();
            }, 500);
        });

        // دالة البحث
        function performSearch() {
            const query = $('#patient-search').val();

            // إرسال طلب AJAX للبحث
            $.ajax({
                url: '{{ route("medical_records.search") }}',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    // تحديث جدول السجلات بنتائج البحث
                    $('.records-table-container').html(response);
                },
                error: function(xhr) {
                    console.error('حدث خطأ أثناء البحث');
                }
            });
        }

        // تفعيل زر النجمة
        $(document).on('click', '.star-btn', function() {
            const button = $(this);
            const patientId = button.data('id');

            // إضافة رمز التحميل
            const icon = button.find('i');
            const originalClass = icon.attr('class');
            icon.removeClass().addClass('fas fa-spinner fa-spin');

            // إرسال طلب AJAX لتبديل حالة النجمة
            $.ajax({
                url: `/medical-records/${patientId}/toggle-star`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // تحديث حالة النجمة في واجهة المستخدم
                        button.data('starred', response.is_starred ? 'true' : 'false');

                        // تغيير أيقونة النجمة
                        if (response.is_starred) {
                            icon.removeClass().addClass('fas fa-star');
                        } else {
                            icon.removeClass().addClass('far fa-star');
                        }
                    } else {
                        // إعادة الأيقونة الأصلية في حالة الفشل
                        icon.removeClass().addClass(originalClass);
                        console.error('فشل تبديل حالة النجمة');
                    }
                },
                error: function(xhr) {
                    // إعادة الأيقونة الأصلية في حالة الخطأ
                    icon.removeClass().addClass(originalClass);
                    console.error('حدث خطأ أثناء تبديل حالة النجمة:', xhr.responseText);
                }
            });
        });

        // تفعيل زر الأرشفة
        $(document).on('click', '.archive-btn', function() {
            const button = $(this);
            const patientId = button.data('id');

            // إضافة رمز التحميل
            const icon = button.find('i');
            const originalClass = icon.attr('class');
            const originalStyle = icon.attr('style') || '';
            icon.removeClass().addClass('fas fa-spinner fa-spin').removeAttr('style');

            // إرسال طلب AJAX لتبديل حالة الأرشفة
            $.ajax({
                url: `/medical-records/${patientId}/toggle-archive`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // تحديث حالة الأرشفة في واجهة المستخدم
                        button.data('archived', response.is_archived ? 'true' : 'false');

                        // تغيير أيقونة الأرشفة
                        if (response.is_archived) {
                            icon.removeClass().addClass('fas fa-archive').removeAttr('style');
                            // إضافة فئة للصف لإظهار أنه مؤرشف
                            button.closest('tr').addClass('archived-row');

                            // إظهار إشعار بدلاً من alert
                            showNotification('تمت أرشفة المريض بنجاح', 'success');
                        } else {
                            icon.removeClass().addClass('fas fa-archive').attr('style', 'opacity: 0.5;');
                            // إزالة فئة الأرشفة من الصف
                            button.closest('tr').removeClass('archived-row');

                            // إظهار إشعار بدلاً من alert
                            showNotification('تم إلغاء أرشفة المريض بنجاح', 'success');
                        }
                    } else {
                        // إعادة الأيقونة الأصلية في حالة الفشل
                        icon.removeClass().addClass(originalClass);
                        if (originalStyle) {
                            icon.attr('style', originalStyle);
                        } else {
                            icon.removeAttr('style');
                        }
                        console.error('فشل تبديل حالة الأرشفة');

                        // إظهار إشعار خطأ
                        showNotification('فشل تبديل حالة الأرشفة', 'error');
                    }
                },
                error: function(xhr) {
                    // إعادة الأيقونة الأصلية في حالة الخطأ
                    icon.removeClass().addClass(originalClass);
                    if (originalStyle) {
                        icon.attr('style', originalStyle);
                    } else {
                        icon.removeAttr('style');
                    }
                    console.error('حدث خطأ أثناء تبديل حالة الأرشفة:', xhr.responseText);

                    // إظهار إشعار خطأ
                    showNotification('حدث خطأ أثناء تبديل حالة الأرشفة', 'error');
                }
            });
        });

        // تفعيل مودال إضافة مريض جديد
        $('#addPatientForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('patients.add-from-dashboard') }}",
                method: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // إغلاق المودال
                        $('#addPatientModal').modal('hide');

                        // عرض رسالة نجاح
                        showNotification(response.message, 'success');

                        // إعادة تحميل الصفحة بعد فترة قصيرة
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // عرض أخطاء التحقق
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            showNotification(value[0], 'error');
                        });
                    } else {
                        // عرض خطأ عام
                        showNotification('حدث خطأ أثناء إضافة المريض', 'error');
                    }
                }
            });
        });
    });

    // دالة لإظهار الإشعارات
    function showNotification(message, type = 'success') {
        // إنشاء عنصر الإشعار
        const notification = $('<div class="notification"></div>');
        notification.addClass(type);
        notification.text(message);

        // إضافة الإشعار إلى الصفحة
        $('body').append(notification);

        // تحريك الإشعار للأعلى بعد إضافته
        setTimeout(() => {
            notification.addClass('show');
        }, 10);

        // إزالة الإشعار بعد 3 ثواني
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection

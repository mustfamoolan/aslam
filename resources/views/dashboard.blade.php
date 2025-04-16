@extends('layouts.app')

@section('title', 'لوحة التحكم - نظام إدارة عيادات الأسنان')

@section('side_slider')
    <!-- إحصائيات سريعة -->
    <div class="section-title">
        <h4>إحصائيات العيادة</h4>
    </div>
    <div class="stats-container">
        <div class="stats-row">
            <div class="stats-card">
                <div class="stats-circle">
                    <span class="stats-number">{{ $todayAppointmentsCount }}</span>
                </div>
                <div class="stats-text">
                    <h5>عدد مواعيد اليوم</h5>
                    <p>مقارنة بـ {{ $yesterdayAppointmentsCount }} بالأمس</p>
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-circle stats-circle-blue">
                    <span class="stats-number">{{ $newPatientsCount }}</span>
                </div>
                <div class="stats-text">
                    <h5>عدد المرضى الجُدد</h5>
                    <p>خلال الشهر الحالي</p>
                </div>
            </div>
        </div>
    </div>

    <!-- كارد الفواتير -->
    <div class="section-title">
        <h4>إحصائيات كل الفواتير</h4>
    </div>
    <div class="invoices-card">
        <div class="invoice-item">
            <div class="invoice-text">عدد الفواتير الكلي</div>
            <div class="invoice-circle invoice-circle-blue">
                <span class="invoice-number">{{ $invoicesCount ?? 0 }}</span>
            </div>
        </div>

        <div class="invoice-item">
            <div class="invoice-text">المبلغ الكُلي</div>
            <div class="invoice-circle invoice-circle-blue">
                <span class="invoice-number">{{ isset($totalAmount) ? (($totalAmount >= 1000) ? round($totalAmount/1000) : $totalAmount) : 0 }}</span>
            </div>
        </div>

        <div class="invoice-item">
            <div class="invoice-text">المبلغ المدفوع</div>
            <div class="invoice-circle invoice-circle-blue">
                <span class="invoice-number">{{ isset($paidAmount) ? (($paidAmount >= 1000) ? round($paidAmount/1000) : $paidAmount) : 0 }}</span>
            </div>
        </div>

        <div class="invoice-item">
            <div class="invoice-text">المبلغ المتبقي</div>
            <div class="invoice-circle invoice-circle-red">
                <span class="invoice-number">{{ isset($remainingAmount) ? (($remainingAmount >= 1000) ? round($remainingAmount/1000) : $remainingAmount) : 0 }}</span>
            </div>
        </div>
    </div>

    <!-- كارد الملاحظات السريعة -->
    <div class="quick-services-container">
        <div class="add-button">
            <div class="add-circle" id="add-note-btn">
                <i class="fas fa-plus"></i>
            </div>
        </div>
        <div class="services-box">
            <div class="services-wrapper" id="notes-container">
                @forelse($latestNotes as $note)
                    <div class="service-note {{ $note->is_important ? 'important-note' : '' }}" data-note-id="{{ $note->id }}">
                        <p>{{ Str::limit($note->content, 50) }}</p>
                    </div>
                @empty
                <div class="service-note">
                        <p>أضف ملاحظاتك هنا</p>
                </div>
                <div class="service-note">
                        <p>يمكنك إضافة ملاحظات سريعة للعيادة</p>
                </div>
                <div class="service-note wide-note">
                        <p>اضغط على زر الإضافة لإضافة ملاحظة جديدة</p>
                </div>
                <div class="service-note">
                        <p>يمكنك تمييز الملاحظات المهمة</p>
                </div>
                <div class="service-note">
                        <p>يمكنك حذف الملاحظات غير المهمة</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- آخر المرضى المضافين -->
    <div class="section-title patients-title">
        <h4>آخر المرضى المضافين</h4>
        <a href="javascript:void(0)" class="view-all-patients" id="view-all-patients">عرض الكل</a>
    </div>
    <div class="latest-patients-container" id="latest-patients-container">
        @forelse($latestPatients as $patient)
            <div class="latest-patient-card">
                <div class="patient-card-avatar">
                    <img src="{{ asset($patient->gender == 'female' ? 'images/22.png' : 'images/11.png') }}" alt="{{ $patient->full_name }}">
                </div>
                <div class="patient-card-info">
                    <h5>{{ $patient->full_name }}</h5>
                    <div class="patient-card-details">
                        <div class="patient-detail">
                            <i class="fas fa-phone"></i>
                            <span>{{ $patient->phone_number }}</span>
                        </div>
                        <div class="patient-detail">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ \Carbon\Carbon::parse($patient->registration_date)->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>
                <div class="patient-card-actions">
                    <a href="{{ route('patients.show', $patient) }}" class="patient-action-btn">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="no-patients-message">
                <p>لا يوجد مرضى مضافين حديثاً</p>
            </div>
        @endforelse
    </div>
@endsection

@section('content')
        <div class="row">
        <!-- القسم الرئيسي -->
        <div class="col-lg-12">
            <!-- Top Bar -->
            <div class="top-bar">

            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <a href="#" class="action-button" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                        <h5>إضافة مريض جديد</h5>
                        <i class="fas fa-user-plus"></i>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="#" class="action-button" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                        <h5>إضافة موعد جديد</h5>
                        <i class="fas fa-calendar-plus"></i>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="#" class="action-button" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                        <h5>إضافة فاتورة جديدة</h5>
                        <i class="fas fa-file-invoice"></i>
                    </a>
                </div>
            </div>

            <!-- Search Bar (تم نقله إلى هنا) -->
            <div class="search-container">
                <div class="search-bar">
                    <input type="text" placeholder="ابحث عن مريض، موعد، فاتورة، أداة..." />
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- قائمة التنبيهات الهامة والفورية -->
            <div class="notifications-dropdown">
                <div class="notifications-header">
                    <h3>التنبيهـات الهامة و الفـورية</h3>
                </div>
                <div class="notification-item" id="main-notification">
                    <div class="notification-icon-container">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title-row">
                            <h4>حدث النظام !</h4>
                            <span class="notification-time">10:26 PM</span>
                        </div>
                        <p class="notification-description">تحديثات كبيرة بانتظارك، قم بتحديث النظام للاستفادة من المميزات الجديدة</p>
                    </div>
                    <div class="notification-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <div class="notification-details" id="notification-details">
                    <div class="notification-item sub-notification">
                        <div class="notification-icon-container">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title-row">
                                <h4>تم تحديث النظام</h4>
                                <span class="notification-time">09:45 PM</span>
                            </div>
                            <p class="notification-description">تم إضافة ميزات جديدة وتحسين أداء النظام في الإصدار 2.3.0</p>
                        </div>
                    </div>

                    <div class="notification-item sub-notification">
                        <div class="notification-icon-container">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title-row">
                                <h4>تذكير بالمواعيد</h4>
                                <span class="notification-time">08:30 PM</span>
                            </div>
                            <p class="notification-description">لديك 3 مواعيد غداً، تأكد من مراجعة جدول المواعيد</p>
                        </div>
                    </div>

                    <div class="notification-item sub-notification">
                        <div class="notification-icon-container">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title-row">
                                <h4>فواتير جديدة</h4>
                                <span class="notification-time">07:15 PM</span>
                            </div>
                            <p class="notification-description">تم إنشاء فاتورتين جديدتين بقيمة إجمالية 350 دينار</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Appointments -->
            <div class="appointments-card">
                <div class="appointments-header">
                    <h2>مواعيـــد اليـــوم</h2>
                    <a href="{{ route('appointments.index') }}" class="view-all">عرض الكل</a>
                </div>
                <div class="appointments-table-container">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>ت</th>
                                <th>الصورة</th>
                                <th>اسم المريض</th>
                                <th>عمر المريض</th>
                                <th>رقـــم الهـــاتف</th>
                                <th>التاريخ</th>
                                <th>وقت الموعد</th>
                                <th>اعـــدادات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointments as $index => $appointment)
                                <tr>
                                    <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="patient-avatar {{ $appointment->patient->gender == 'female' ? '' : 'default-avatar' }}">
                                            <img src="{{ asset($appointment->patient->gender == 'female' ? 'images/22.png' : 'images/11.png') }}" alt="Patient">
                                        </div>
                                    </td>
                                    <td>{{ $appointment->patient->full_name }}</td>
                                    <td>{{ $appointment->patient->age }}</td>
                                    <td>{{ $appointment->patient->phone_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y/m/d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td class="actions-cell">
                                        <!-- تعديل زر الحذف ليستخدم AJAX -->
                                        <button type="button" class="delete-btn" data-id="{{ $appointment->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="edit-btn" data-id="{{ $appointment->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد مواعيد لليوم</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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

    <!-- مودال إضافة ملاحظة جديدة -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNoteModalLabel">إضافة ملاحظة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addNoteForm">
                        @csrf
                        <input type="hidden" name="dental_clinic_id" value="{{ $clinic->id }}">
                        <div class="mb-3">
                            <label for="content" class="form-label">محتوى الملاحظة</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_important" name="is_important" value="1">
                            <label class="form-check-label" for="is_important">ملاحظة مهمة</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="saveNoteBtn">حفظ الملاحظة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال تعديل الملاحظة -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoteModalLabel">تعديل الملاحظة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNoteForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_note_id" name="note_id">
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">محتوى الملاحظة</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="4" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_important" name="is_important" value="1">
                            <label class="form-check-label" for="edit_is_important">ملاحظة مهمة</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteNoteBtn">حذف الملاحظة</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="updateNoteBtn">تحديث الملاحظة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال إضافة فاتورة جديدة -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content appointment-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">إضافة فاتورة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addInvoiceForm" action="{{ route('invoices.store') }}" method="POST">
                        @csrf

                        <!-- حقل البحث عن المريض -->
                        <div class="search-patient-container mb-4">
                            <div class="search-input-wrapper">
                                <input type="text" class="form-control search-patient-input" id="patient_search_invoice" placeholder="ابحث عن مريض لإضافة فاتورة له">
                                <button type="button" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="appointment-form-row">
                            <div class="appointment-form-col">
                                <!-- حقول الجانب الأيمن -->
                                <div class="mb-4">
                                    <label for="patient_id_invoice" class="form-label">اسم المريض</label>
                                    <div class="patient-select-wrapper">
                                        <select class="form-select" id="patient_id_invoice" name="patient_id" required>
                                            <option value="" selected disabled>قم باختياره من قائمة البحث</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="invoice_type" class="form-label">اختيار فاتورة</label>
                                    <div class="session-select-wrapper">
                                        <select class="form-select" id="invoice_type" name="invoice_type" required>
                                            <option value="" selected disabled>اضغط لاختيارها</option>
                                            @foreach($invoiceTypes as $type)
                                                <option value="{{ $type->name }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="issue_date" class="form-label">تاريخ إصدارها</label>
                                    <div class="date-picker-container">
                                        <input type="text" class="form-control date-display" id="issue_date_display" placeholder="اضغط لاختياره" readonly>
                                        <input type="hidden" id="issue_date" name="issue_date" required>
                                        <div class="date-picker-popup" id="issue_date_picker_popup">
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
                                            <div class="calendar-days" id="issue_date_calendar_days">
                                                <!-- سيتم إنشاء الأيام بواسطة JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="amount" class="form-label">المبلغ الإجمالي</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="أدخل المبلغ الإجمالي" required>
                                </div>
                            </div>

                            <div class="appointment-form-col">
                                <!-- حقول الجانب الأيسر -->
                                <div class="mb-4">
                                    <label for="paid_amount" class="form-label">المبلغ المدفوع</label>
                                    <input type="number" class="form-control" id="paid_amount" name="paid_amount" placeholder="أدخل المبلغ المدفوع" required>
                                </div>

                                <div class="mb-4">
                                    <label for="session_title" class="form-label">عنوان الجلسة</label>
                                    <input type="text" class="form-control" id="session_title" name="session_title" placeholder="أدخل عنوان الجلسة">
                                </div>

                                <div class="mb-4">
                                    <label for="note" class="form-label">ملاحظة</label>
                                    <textarea class="form-control" id="note" name="note" rows="4" placeholder="اكتب ملاحظة سريعة وهامة"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">إضافة الفاتورة</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال تعديل الموعد -->
    <div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content appointment-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAppointmentModalLabel">تعديل الموعد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAppointmentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_appointment_id" name="appointment_id">

                        <!-- حقل البحث عن المريض -->
                        <div class="search-patient-container mb-4">
                            <div class="search-input-wrapper">
                                <input type="text" class="form-control search-patient-input" id="edit_patient_search" placeholder="ابحث عن مريض لحجز موعد له">
                                <button type="button" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="appointment-form-row">
                            <div class="appointment-form-col">
                                <!-- حقول الجانب الأيمن -->
                                <div class="mb-4">
                                    <label for="edit_patient_id" class="form-label">اسم المريض</label>
                                    <div class="patient-select-wrapper">
                                        <select class="form-select" id="edit_patient_id" name="patient_id" required>
                                            <option value="" selected disabled>قم باختياره من قائمة البحث</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- حقل التاريخ -->
                                <div class="mb-4">
                                    <label for="edit_appointment_date" class="form-label">تاريخ الموعد</label>
                                    <div class="date-picker-container">
                                        <input type="text" class="form-control date-display" id="edit_date_display" placeholder="اضغط لاختياره" readonly>
                                        <input type="hidden" id="edit_appointment_date" name="appointment_date" required>
                                        <div class="date-picker-popup" id="edit_date_picker_popup">
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
                                            <div class="calendar-days" id="edit_calendar_days">
                                                <!-- سيتم إنشاء الأيام بواسطة JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- حقل الوقت -->
                                <div class="mb-4">
                                    <label for="edit_appointment_time" class="form-label">وقت الموعد</label>
                                    <div class="time-picker-container">
                                        <input type="text" class="form-control time-display" id="edit_time_display" placeholder="اضغط لاختياره" readonly>
                                        <input type="hidden" id="edit_appointment_time" name="appointment_time" required>
                                        <div class="time-picker-popup" id="edit_time_picker_popup">
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
                                    <label for="edit_session_type" class="form-label">نوع الجلسة</label>
                                    <div class="session-select-wrapper">
                                        <select class="form-select" id="edit_session_type" name="session_type">
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
                                    <label for="edit_amount" class="form-label">المبلغ</label>
                                    <input type="number" class="form-control" id="edit_amount" name="amount" placeholder="اكتب المبلغ">
                                </div>
                            </div>

                            <div class="appointment-form-col">
                                <!-- حقول الجانب الأيسر -->
                                <div class="mb-4">
                                    <label for="edit_note" class="form-label">ملاحظة</label>
                                    <textarea class="form-control" id="edit_note" name="note" rows="6" placeholder="اكتب ملاحظة سريعة وهامة"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="edit_session_title" class="form-label">عنوان الجلسة</label>
                                    <input type="text" class="form-control" id="edit_session_title" name="session_title" placeholder="عنوان الجلسة">
                                </div>

                                <div class="mb-4">
                                    <label for="edit_status" class="form-label">حالة الموعد</label>
                                    <div class="session-select-wrapper">
                                        <select class="form-select" id="edit_status" name="status" required>
                                            <option value="pending">قيد الانتظار</option>
                                            <option value="completed">مكتمل</option>
                                            <option value="cancelled">ملغي</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تحديث الموعد</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<!-- إضافة مكتبة toastr لعرض الإشعارات -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    /* استيراد خط Alexandria */
    @import url('https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&display=swap');

    /* تطبيق الخط على السلايدر الجانبي */
    .side-slider {
        font-family: 'Alexandria', sans-serif;
    }

    /* أنماط العناوين */
    .section-title {
        margin-top: 30px;
        margin-bottom: 25px;
        color: white;
        padding: 0 5px;
    }

    /* عنوان آخر المرضى المضافين */
    .section-title.patients-title {
        margin-top: 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        position: relative;
        display: inline-block;
        padding-bottom: 15px;
        font-family: 'Alexandria', sans-serif;
    }

    .view-all-patients {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        padding: 0;
        transition: all 0.2s ease;
        font-family: 'Alexandria', sans-serif;
        font-weight: 400;
    }

    .view-all-patients:hover {
        color: white;
        text-decoration: none;
    }

    /* أنماط الإحصائيات المطابقة للصورة */
    .stats-container {
        margin-top: 30px;
        margin-bottom: 40px;
        position: relative;
    }

    .stats-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .stats-card {
        position: relative;
        background-color: #286984 !important;
        border-radius: 10px;
        padding: 15px;
        padding-top: 50px;
        width: calc(50% - 5px);
        height: 130px;
        text-align: center;
        overflow: visible;
        font-family: 'Alexandria', sans-serif;
    }

    .stats-circle {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: radial-gradient(circle at center, rgba(173, 216, 230, 0.8) 0%, rgba(173, 216, 230, 0.4) 70%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        z-index: 10;
    }

    .stats-circle-blue {
        background: radial-gradient(circle at center, rgba(64, 224, 208, 0.8) 0%, rgba(64, 224, 208, 0.4) 70%);
    }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        color: white;
        font-family: 'Alexandria', sans-serif;
    }

    .stats-text {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
    }

    .stats-text h5 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: white;
        font-family: 'Alexandria', sans-serif;
    }

    .stats-text p {
        margin: 5px 0 0 0;
        font-size: 8px;
        color: rgba(255, 255, 255, 0.7);
        font-family: 'Alexandria', sans-serif;
    }

    /* أنماط كارد الفواتير */
    .invoices-card {
        background-color: #286984;
        border-radius: 10px;
        padding: 15px;
        margin-top: 30px;
        margin-bottom: 30px;
        font-family: 'Alexandria', sans-serif;
    }

    .invoice-item {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #3a7d99;
        border-radius: 50px;
        padding: 8px 15px 8px 0;
        margin-bottom: 10px;
        height: 45px;
        overflow: visible;
    }

    .invoice-item:last-child {
        margin-bottom: 0;
    }

    .invoice-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #0066cc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0;
        position: absolute;
        left: 0;
        top: 0;
        box-shadow: 0 0 15px rgba(0, 102, 204, 0.5);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .invoice-circle-blue {
        background: linear-gradient(135deg, #0066cc, #004c99);
    }

    .invoice-circle-red {
        background: linear-gradient(135deg, #cc3333, #992626);
        box-shadow: 0 0 15px rgba(204, 51, 51, 0.5);
    }

    .invoice-number {
        font-size: 18px;
        font-weight: 700;
        color: white;
        font-family: 'Alexandria', sans-serif;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .invoice-text {
        font-size: 14px;
        font-weight: 600;
        color: white;
        font-family: 'Alexandria', sans-serif;
        text-align: right;
        flex: 1;
        margin-right: 15px;
    }

    /* أنماط كارد الخدمات السريعة */
    .quick-services-container {
        margin-top: 30px;
        margin-bottom: 30px;
        position: relative;
    }

    .add-button {
        position: absolute;
        top: 10px;
        left: -10px;
        z-index: 10;
    }

    .add-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #bcccd7;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 6px solid #22577A;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-circle:hover {
        transform: scale(1.05);
    }

    .add-circle i {
        color: #22577A;
        font-size: 18px;
    }

    .services-box {
        width: 270px;
        height: 188px;
        position: relative;
        top: 36px;
        left: 9px;
        background-color: #bcccd7;
        border-radius: 12.38px;
        padding: 15px;
        box-shadow: inset 0 5px 10px rgba(0, 0, 0, 0.15);
    }

    .services-wrapper {
        position: relative;
        top: 20px;
    }

    .service-note {
        width: 70px;
        height: 62px;
        position: absolute;
        background-color: #FFFA96;
        border-radius: 5px;
        padding: 8px 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.29);
    }

    .service-note:nth-child(1) {
        top: 0;
        left: 0;
    }

    .service-note:nth-child(2) {
        top: 0;
        left: 80px;
    }

    .service-note:nth-child(3) {
        top: 0;
        left: 160px;
        width: 70px;
    }

    .service-note:nth-child(4) {
        top: 72px;
        left: 0;
    }

    .service-note:nth-child(5) {
        top: 72px;
        left: 80px;
    }

    .service-note:nth-child(6) {
        top: 72px;
        left: 160px;
    }

    .service-note::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: repeating-linear-gradient(
            90deg,
            #ccc,
            #ccc 3px,
            transparent 3px,
            transparent 10px
        );
    }

    .service-note p {
        margin: 0;
        font-size: 5px;
        font-weight: 600;
        color: #368595;
        font-family: 'Alexandria', sans-serif;
        line-height: 1.2;
    }

    /* أنماط أزرار الإجراءات */
    .action-button {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        background-color: #3B6B8A;
        background-image: url('{{ asset('images/button-pattern.png') }}');
        border-radius: 8px;
        padding: 25px 20px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .action-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background-size: cover;
        background-position: center;
        opacity: 0.1;
        z-index: 1;
    }

    .action-button:hover {
        background-color: #2c6a85;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    .action-button h5 {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: white;
        font-family: 'Alexandria', sans-serif;
        text-align: right;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .action-button i {
        font-size: 18px;
        color: #3B6B8A;
        background-color: #cddbe8;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        position: relative;
        z-index: 2;
    }

    /* أنماط جدول المواعيد */
    .appointments-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .appointments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .appointments-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #22577A;
        margin: 0;
    }

    .view-all {
        color: #22577A;
        font-size: 14px;
        text-decoration: none;
        font-weight: 500;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    .appointments-table-container {
        overflow-x: auto;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .appointments-table th {
        background-color: #f8f9fa;
        color: #22577A;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .appointments-table td {
        padding: 12px 15px;
        text-align: center;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #f5f5f5;
    }

    .appointments-table tr:hover {
        background-color: #f9f9f9;
    }

    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .default-avatar {
        background-color: #e9ecef;
        color: #adb5bd;
        font-size: 18px;
    }

    .actions-cell {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .delete-btn, .edit-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .edit-btn {
        background-color: #17a2b8;
    }

    .delete-btn:hover, .edit-btn:hover {
        transform: scale(1.1);
    }

    /* أنماط أخرى */
    .top-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 30px;
    }

    /* تعديل تصميم مربع البحث */
    .search-container {
        display: flex;
        justify-content: flex-start;
        width: 100%;
        margin-bottom: 25px;
        padding-left: 15px;
    }

    .search-bar {
        display: flex;
        align-items: center;
        background-color: white;
        border-radius: 8px;
        padding: 0;
        width: 100%;
        max-width: 730px;
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

    .notifications {
        position: relative;
        margin-right: 20px;
    }

    .notification-icon {
        font-size: 1.5rem;
        color: #777;
        cursor: pointer;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* أنماط قائمة التنبيهات */
    .notifications-dropdown {
        background-color: #fffee9;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin: 0 15px 25px 15px;
        overflow: hidden;
    }

    .notifications-header {
        background-color: transparent;
        padding: 15px 20px;
        border-bottom: 1px solid #f5f0c0;
        text-align: right;
    }

    .notifications-header h3 {
        margin: 0;
        color: #22577A;
        font-size: 18px;
        font-weight: 600;
        font-family: 'Alexandria', sans-serif;
    }

    .notification-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .notification-item:hover {
        background-color: #f5f5f5;
    }

    .notification-icon-container {
        margin-left: 15px;
    }

    .notification-icon-container i {
        font-size: 20px;
        color: #22577A;
    }

    .notification-content {
        flex: 1;
        text-align: right;
    }

    .notification-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .notification-content h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
        font-family: 'Alexandria', sans-serif;
    }

    .notification-description {
        margin: 0;
        font-size: 13px;
        color: #666;
        font-family: 'Alexandria', sans-serif;
        line-height: 1.4;
    }

    .notification-time {
        font-size: 12px;
        color: #888;
        font-family: 'Alexandria', sans-serif;
        white-space: nowrap;
        margin-right: 10px;
    }

    .notification-toggle {
        margin-right: 10px;
    }

    .notification-toggle i {
        font-size: 14px;
        color: #aaa;
        transition: transform 0.3s ease;
    }

    .notification-item.expanded .notification-toggle i {
        transform: rotate(180deg);
    }

    .notification-details {
        display: none;
        background-color: #fffef2;
        border-top: 1px dashed #f5f0c0;
    }

    .sub-notification {
        padding: 12px 20px;
        background-color: #fffef2;
    }

    .sub-notification:hover {
        background-color: #fffdd0;
    }

    .notification-item.expanded .notification-toggle i {
        transform: rotate(180deg);
    }

    /* أنماط مودال إضافة مريض جديد */
    .add-patient-container {
        background-color: #f2f7ff;
        border-radius: 10px;
        overflow: hidden;
    }

    .add-patient-title {
        background-color: #f2f7ff;
        color: white;
        padding: 15px 20px;
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        color: #22577A;
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

    .add-patient-btn {
        background-color: #3B6B8A;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'Alexandria', sans-serif;
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

    .add-patient-btn:hover {
        background-color: #2c5a76;
    }

    .cancel-btn:hover {
        background-color: #f8f9fa;
    }

    /* أنماط مودال إضافة موعد جديد */
    .add-appointment-container {
        background-color: #f2f7ff;
        border-radius: 10px;
        overflow: hidden;
    }

    .add-appointment-title {
        background-color: #f2f7ff;
        color: #22577A;
        padding: 15px 20px;
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        font-family: 'Alexandria', sans-serif;
    }

    .add-appointment-content {
        padding: 20px;
    }

    .select-wrapper {
        position: relative;
    }

    .select-arrow {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #22577A;
        pointer-events: none;
    }

    .add-appointment-btn {
        background-color: #3B6B8A;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-family: 'Alexandria', sans-serif;
    }

    .add-appointment-btn:hover {
        background-color: #2c5a76;
    }

    /* أنماط آخر المرضى المضافين */
    .latest-patients-container {
        margin-top: 15px;
        margin-bottom: 25px;
    }

    .latest-patient-card {
        display: flex;
        align-items: center;
        background-color: #286984;
        border-radius: 8px;
        padding: 8px 10px;
        margin-bottom: 8px;
        position: relative;
        transition: all 0.3s ease;
    }

    .latest-patient-card:hover {
        background-color: #3a7d99;
        transform: translateY(-2px);
    }

    .patient-card-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #cddbe8;
        margin-left: 10px;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .patient-card-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .patient-card-info {
        flex: 1;
    }

    .patient-card-info h5 {
        margin: 0 0 3px 0;
        font-size: 12px;
        font-weight: 600;
        color: white;
        font-family: 'Alexandria', sans-serif;
    }

    .patient-card-details {
        display: flex;
        flex-direction: column;
    }

    .patient-detail {
        display: flex;
        align-items: center;
        margin-bottom: 2px;
    }

    .patient-detail i {
        font-size: 8px;
        color: rgba(255, 255, 255, 0.7);
        margin-left: 4px;
    }

    .patient-detail span {
        font-size: 8px;
        color: rgba(255, 255, 255, 0.7);
        font-family: 'Alexandria', sans-serif;
    }

    .patient-card-actions {
        display: flex;
        align-items: center;
    }

    .patient-action-btn {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .patient-action-btn:hover {
        background-color: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .patient-action-btn i {
        font-size: 10px;
    }

    .no-patients-message {
        text-align: center;
        padding: 10px;
        color: rgba(255, 255, 255, 0.7);
        font-family: 'Alexandria', sans-serif;
        font-size: 12px;
    }

    /* أنماط الملاحظات المهمة */
    .service-note.important-note {
        background-color: #FFECB3;
        border-right: 3px solid #FFC107;
    }

    .service-note.important-note p {
        color: #E65100;
        font-weight: 700;
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
</style>
@endsection

@section('scripts')
<!-- إضافة مكتبة jQuery إذا لم تكن موجودة بالفعل -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- إضافة مكتبة toastr لعرض الإشعارات -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // إعدادات toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "rtl": true
    };

    document.addEventListener('DOMContentLoaded', function() {
        const mainNotification = document.getElementById('main-notification');
        const notificationDetails = document.getElementById('notification-details');

        mainNotification.addEventListener('click', function() {
            this.classList.toggle('expanded');

            if (notificationDetails.style.display === 'none' || notificationDetails.style.display === '') {
                notificationDetails.style.display = 'block';
            } else {
                notificationDetails.style.display = 'none';
            }
        });

        const maleRadio = document.getElementById('male');
        const femaleRadio = document.getElementById('female');
        const patientAvatarImg = document.getElementById('patient-avatar-img');

        if (patientAvatarImg) {
            patientAvatarImg.src = "{{ asset('images/11.png') }}";
        }

        if (maleRadio && femaleRadio && patientAvatarImg) {
            maleRadio.addEventListener('change', function() {
                if (this.checked) {
                    patientAvatarImg.src = "{{ asset('images/11.png') }}";
                }
            });

            femaleRadio.addEventListener('change', function() {
                if (this.checked) {
                    patientAvatarImg.src = "{{ asset('images/22.png') }}";
                }
            });
        }
    });

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
                    toastr.success(response.message);

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
                        // عرض الأخطاء باستخدام toastr أو أي طريقة أخرى
                        toastr.error(value[0]);
                    });
                } else {
                    // عرض خطأ عام
                    toastr.error('حدث خطأ أثناء إضافة المريض');
                }
            }
        });
    });

    // إضافة كود JavaScript لمعالجة إرسال نموذج إضافة موعد
    document.addEventListener('DOMContentLoaded', function() {
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

    // إضافة كود لعرض المزيد من المرضى
    document.addEventListener('DOMContentLoaded', function() {
        const viewAllBtn = document.getElementById('view-all-patients');
        const patientsContainer = document.getElementById('latest-patients-container');
        const sideSlider = document.querySelector('.side-slider');
        const otherSections = document.querySelectorAll('.side-slider > div:not(.section-title.patients-title):not(.latest-patients-container)');
        const patientsTitleSection = document.querySelector('.section-title.patients-title');

        let isExpanded = false;
        let isLoading = false;

        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', function() {
                if (isLoading) return;

                if (isExpanded) {
                    // إذا كانت القائمة موسعة، قم بإرجاعها إلى الحالة الأصلية
                    isLoading = true;

                    // تغيير نص الزر
                    viewAllBtn.textContent = 'عرض الكل';

                    // إظهار العناصر الأخرى في السلايدر
                    otherSections.forEach(section => {
                        section.style.display = '';
                    });

                    // إعادة تحميل المرضى الأصليين (4 فقط)
                    $.ajax({
                        url: "{{ route('dashboard.latest-patients') }}",
                        method: "GET",
                        data: { limit: 4 },
                        success: function(response) {
                            patientsContainer.innerHTML = response;

                            // إعادة موضع قسم المرضى إلى مكانه الأصلي
                            patientsTitleSection.style.marginTop = '60px';

                            isExpanded = false;
                            isLoading = false;
                        },
                        error: function() {
                            toastr.error('حدث خطأ أثناء تحميل البيانات');
                            isLoading = false;
                        }
                    });
                } else {
                    // إذا كانت القائمة غير موسعة، قم بتوسيعها
                    isLoading = true;

                    // تغيير نص الزر
                    viewAllBtn.textContent = 'عرض أقل';

                    // إخفاء العناصر الأخرى في السلايدر
                    otherSections.forEach(section => {
                        section.style.display = 'none';
                    });

                    // تحريك قسم المرضى إلى أعلى السلايدر
                    patientsTitleSection.style.marginTop = '20px';

                    // تحميل جميع المرضى
                    $.ajax({
                        url: "{{ route('dashboard.latest-patients') }}",
                        method: "GET",
                        data: { limit: 20 },
                        success: function(response) {
                            patientsContainer.innerHTML = response;

                            // التمرير إلى أعلى السلايدر
                            sideSlider.scrollTop = 0;

                            isExpanded = true;
                            isLoading = false;
                        },
                        error: function() {
                            toastr.error('حدث خطأ أثناء تحميل البيانات');
                            isLoading = false;
                        }
                    });
                }
            });
        }
    });

    // إضافة كود لإدارة الملاحظات
    document.addEventListener('DOMContentLoaded', function() {
        // فتح مودال إضافة ملاحظة جديدة
        const addNoteBtn = document.getElementById('add-note-btn');
        if (addNoteBtn) {
            addNoteBtn.addEventListener('click', function() {
                const addNoteModal = new bootstrap.Modal(document.getElementById('addNoteModal'));
                addNoteModal.show();
            });
        }

        // حفظ ملاحظة جديدة
        const saveNoteBtn = document.getElementById('saveNoteBtn');
        if (saveNoteBtn) {
            saveNoteBtn.addEventListener('click', function() {
                const formData = new FormData(document.getElementById('addNoteForm'));

                fetch('{{ route("notes.quick-add") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // إغلاق المودال
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addNoteModal'));
                        modal.hide();

                        // إعادة تحميل الصفحة
                        window.location.reload();
                    } else {
                        // عرض رسائل الخطأ
                        toastr.error('حدث خطأ أثناء إضافة الملاحظة');
                        console.error(data.errors);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('حدث خطأ أثناء إضافة الملاحظة');
                });
            });
        }

        // إضافة معالج النقر على الملاحظات لفتح مودال التعديل
        const noteElements = document.querySelectorAll('.service-note[data-note-id]');
        noteElements.forEach(note => {
            note.addEventListener('click', function() {
                const noteId = this.dataset.noteId;
                const noteContent = this.querySelector('p').textContent;
                const isImportant = this.classList.contains('important-note');

                // تعبئة بيانات المودال
                document.getElementById('edit_note_id').value = noteId;
                document.getElementById('edit_content').value = noteContent;
                document.getElementById('edit_is_important').checked = isImportant;

                // فتح المودال
                const editNoteModal = new bootstrap.Modal(document.getElementById('editNoteModal'));
                editNoteModal.show();
            });
        });

        // تحديث الملاحظة
        const updateNoteBtn = document.getElementById('updateNoteBtn');
        if (updateNoteBtn) {
            updateNoteBtn.addEventListener('click', function() {
                const noteId = document.getElementById('edit_note_id').value;
                const content = document.getElementById('edit_content').value;
                const isImportant = document.getElementById('edit_is_important').checked;

                // إظهار رسالة تحميل
                toastr.info('جاري تحديث الملاحظة...');

                $.ajax({
                    url: `/notes/${noteId}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        content: content,
                        is_important: isImportant ? 1 : 0
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success('تم تحديث الملاحظة بنجاح');

                            // إغلاق المودال
                            const modal = bootstrap.Modal.getInstance(document.getElementById('editNoteModal'));
                            modal.hide();

                            // تأخير قصير قبل إعادة تحميل الصفحة
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error('حدث خطأ أثناء تحديث الملاحظة');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error('حدث خطأ أثناء تحديث الملاحظة: ' + error);
                    }
                });
            });
        }

        // حذف الملاحظة
        const deleteNoteBtn = document.getElementById('deleteNoteBtn');
        if (deleteNoteBtn) {
            deleteNoteBtn.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف هذه الملاحظة؟')) {
                    const noteId = document.getElementById('edit_note_id').value;

                    // إظهار رسالة تحميل
                    toastr.info('جاري حذف الملاحظة...');

                    $.ajax({
                        url: `/notes/${noteId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success('تم حذف الملاحظة بنجاح');

                                // إغلاق المودال
                                const modal = bootstrap.Modal.getInstance(document.getElementById('editNoteModal'));
                                modal.hide();

                                // تأخير أطول قبل إعادة تحميل الصفحة
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error('حدث خطأ أثناء حذف الملاحظة');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            toastr.error('حدث خطأ أثناء حذف الملاحظة: ' + error);
                        }
                    });
                }
            });
        }
    });

    // إضافة كود لمعالجة زر "عرض الكل" في قسم المواعيد
    document.addEventListener('DOMContentLoaded', function() {
        const viewAllAppointmentsBtn = document.querySelector('.appointments-header .view-all');
        const appointmentsCard = document.querySelector('.appointments-card');
        const mainContent = document.querySelector('.col-lg-12');
        const appointmentsTable = document.querySelector('.appointments-table-container');

        // حفظ الموقع الأصلي لكارد المواعيد
        const originalParent = appointmentsCard.parentNode;
        const originalNextSibling = appointmentsCard.nextElementSibling;

        let isAppointmentsExpanded = false;

        if (viewAllAppointmentsBtn) {
            viewAllAppointmentsBtn.addEventListener('click', function(e) {
                e.preventDefault();

                if (!isAppointmentsExpanded) {
                    // تغيير نص الزر
                    viewAllAppointmentsBtn.textContent = 'عرض أقل';

                    // إخفاء جميع العناصر في المحتوى الرئيسي ما عدا كارد المواعيد
                    Array.from(mainContent.children).forEach(child => {
                        if (!child.contains(appointmentsCard)) {
                            child.style.display = 'none';
                        }
                    });

                    // نقل كارد المواعيد إلى أعلى المحتوى
                    mainContent.prepend(appointmentsCard);

                    // تكبير كارد المواعيد
                    appointmentsCard.style.width = '100%';
                    appointmentsCard.style.maxHeight = 'none';
                    appointmentsCard.style.height = 'calc(100vh - 100px)';
                    appointmentsCard.style.marginTop = '0';
                    appointmentsCard.style.position = 'relative';
                    appointmentsCard.style.zIndex = '1000';

                    // تكبير جدول المواعيد
                    appointmentsTable.style.maxHeight = 'calc(100vh - 200px)';

                    // تحميل جميع مواعيد اليوم
                    $.ajax({
                        url: "{{ route('dashboard.today-appointments') }}",
                        method: "GET",
                        success: function(response) {
                            appointmentsTable.innerHTML = response;
                            isAppointmentsExpanded = true;
                        },
                        error: function() {
                            toastr.error('حدث خطأ أثناء تحميل البيانات');
                        }
                    });
                } else {
                    // إعادة الوضع إلى الحالة الأصلية
                    viewAllAppointmentsBtn.textContent = 'عرض الكل';

                    // إعادة كارد المواعيد إلى موقعه الأصلي
                    if (originalNextSibling) {
                        originalParent.insertBefore(appointmentsCard, originalNextSibling);
                    } else {
                        originalParent.appendChild(appointmentsCard);
                    }

                    // إظهار جميع العناصر في المحتوى الرئيسي
                    Array.from(mainContent.children).forEach(child => {
                        child.style.display = '';
                    });

                    // إعادة حجم كارد المواعيد
                    appointmentsCard.style.width = '';
                    appointmentsCard.style.maxHeight = '';
                    appointmentsCard.style.height = '';
                    appointmentsCard.style.marginTop = '';
                    appointmentsCard.style.position = '';
                    appointmentsCard.style.zIndex = '';

                    // إعادة حجم جدول المواعيد
                    appointmentsTable.style.maxHeight = '';

                    // إعادة تحميل المواعيد المحدودة
                    $.ajax({
                        url: "{{ route('dashboard.today-appointments') }}",
                        method: "GET",
                        data: { limit: 11 },
                        success: function(response) {
                            appointmentsTable.innerHTML = response;
                            isAppointmentsExpanded = false;
                        },
                        error: function() {
                            toastr.error('حدث خطأ أثناء تحميل البيانات');
                        }
                    });
                }
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
    });

    // تحديث الأوقات المتاحة عند اختيار تاريخ
    function updateAvailableTimes(date) {
        if (!date) return;

        // عرض مؤشر التحميل
        const timePickerPopup = document.getElementById('time_picker_popup');
        if (timePickerPopup) {
            timePickerPopup.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> جاري تحميل الأوقات المتاحة...</div>';
        }

        // طلب الأوقات المتاحة من الخادم
        $.ajax({
            url: "{{ route('dashboard.available-times') }}",
            method: "GET",
            data: { date: date },
            success: function(response) {
                // إنشاء قائمة الأوقات المتاحة
                let timeSlotHtml = '<div class="time-slots">';

                response.forEach(slot => {
                    if (slot.available) {
                        timeSlotHtml += `<div class="time-slot" data-time="${slot.time}">${slot.formatted}</div>`;
                    } else {
                        timeSlotHtml += `<div class="time-slot unavailable" title="هذا الوقت محجوز">${slot.formatted}</div>`;
                    }
                });

                timeSlotHtml += '</div>';

                // تحديث محتوى قائمة الأوقات
                if (timePickerPopup) {
                    timePickerPopup.innerHTML = timeSlotHtml;

                    // إعادة تعيين أحداث النقر على الأوقات المتاحة
                    const newTimeSlots = document.querySelectorAll('.time-slot:not(.unavailable)');
                    newTimeSlots.forEach(slot => {
                        slot.addEventListener('click', function() {
                            // إزالة التحديد السابق
                            newTimeSlots.forEach(s => s.classList.remove('selected'));

                            // تحديد الوقت الجديد
                            slot.classList.add('selected');

                            // تحديث حقل الوقت
                            const time = slot.getAttribute('data-time');
                            document.getElementById('appointment_time').value = time;

                            // تحديث العرض
                            document.getElementById('time_display').value = slot.textContent;

                            // إغلاق قائمة الأوقات
                            timePickerPopup.style.display = 'none';
                        });
                    });
                }
            },
            error: function() {
                if (timePickerPopup) {
                    timePickerPopup.innerHTML = '<div class="error-message">حدث خطأ أثناء تحميل الأوقات المتاحة</div>';
                }
            }
        });
    }

    // تعديل نموذج إضافة الموعد ليستخدم AJAX
    $(document).ready(function() {
        $('#addAppointmentForm').on('submit', function(e) {
            e.preventDefault();

            // عرض مؤشر التحميل
            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.text();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...');
            submitBtn.prop('disabled', true);

            // إرسال البيانات باستخدام AJAX
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودال
                    $('#addAppointmentModal').modal('hide');

                    // إعادة تعيين النموذج
                    $('#addAppointmentForm')[0].reset();

                    // تحديث قائمة المواعيد
                    refreshTodayAppointments();

                    // عرض رسالة نجاح
                    toastr.success('تم إضافة الموعد بنجاح');
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) { // خطأ في التحقق من البيانات
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(errors[field][0]);
                        }
                    } else {
                        toastr.error('حدث خطأ أثناء إضافة الموعد');
                    }
                },
                complete: function() {
                    // إعادة زر الإرسال إلى حالته الأصلية
                    submitBtn.html(originalBtnText);
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // دالة لتحديث قائمة مواعيد اليوم
        function refreshTodayAppointments() {
            const appointmentsTable = $('.appointments-table-container');

            // عرض مؤشر التحميل
            appointmentsTable.html('<div class="loading-spinner text-center my-4"><i class="fas fa-spinner fa-spin"></i> جاري تحميل المواعيد...</div>');

            // جلب مواعيد اليوم المحدثة
            $.ajax({
                url: "{{ route('dashboard.today-appointments') }}",
                method: "GET",
                data: { limit: 11 }, // عدد المواعيد المعروضة في لوحة التحكم
                success: function(response) {
                    appointmentsTable.html(response);

                    // تحديث عداد المواعيد في الإحصائيات
                    $.ajax({
                        url: "{{ route('dashboard') }}",
                        method: "GET",
                        dataType: "json",
                        data: { get_stats: true },
                        success: function(stats) {
                            $('.stats-number:first').text(stats.todayAppointmentsCount);
                        }
                    });
                },
                error: function() {
                    appointmentsTable.html('<div class="error-message text-center my-4">حدث خطأ أثناء تحميل المواعيد</div>');
                }
            });
        }
    });

    // تفعيل مودال إضافة فاتورة جديدة
    $(document).ready(function() {
        // تفعيل حقل التاريخ
        const issueDateDisplay = document.getElementById('issue_date_display');
        const issueDateInput = document.getElementById('issue_date');
        const issueDatePicker = document.getElementById('issue_date_picker_popup');
        const issueDateCalendarDays = document.getElementById('issue_date_calendar_days');
        const issueDateCurrentMonth = document.querySelector('#issue_date_picker_popup .current-month');
        const issueDatePrevMonthBtn = document.querySelector('#issue_date_picker_popup .prev-month');
        const issueDateNextMonthBtn = document.querySelector('#issue_date_picker_popup .next-month');

        let issueDateCurrentDate = new Date();
        let issueDateSelectedDate = null;

        // عرض التقويم عند النقر على حقل التاريخ
        if (issueDateDisplay) {
            issueDateDisplay.addEventListener('click', function() {
                issueDatePicker.style.display = 'block';
                renderIssueDateCalendar(issueDateCurrentDate);
            });

            // إغلاق التقويم عند النقر خارجه
            document.addEventListener('click', function(e) {
                if (!issueDateDisplay.contains(e.target) && !issueDatePicker.contains(e.target)) {
                    issueDatePicker.style.display = 'none';
                }
            });

            // أزرار التنقل بين الشهور
            issueDatePrevMonthBtn.addEventListener('click', function() {
                issueDateCurrentDate.setMonth(issueDateCurrentDate.getMonth() - 1);
                renderIssueDateCalendar(issueDateCurrentDate);
            });

            issueDateNextMonthBtn.addEventListener('click', function() {
                issueDateCurrentDate.setMonth(issueDateCurrentDate.getMonth() + 1);
                renderIssueDateCalendar(issueDateCurrentDate);
            });
        }

        // دالة لعرض التقويم
        function renderIssueDateCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();

            // تحديث عنوان الشهر
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            issueDateCurrentMonth.textContent = `${monthNames[month]} ${year}`;

            // الحصول على اليوم الأول من الشهر
            const firstDay = new Date(year, month, 1);
            const startingDay = firstDay.getDay(); // 0 = الأحد، 1 = الاثنين، ...

            // الحصول على عدد أيام الشهر
            const lastDay = new Date(year, month + 1, 0);
            const totalDays = lastDay.getDate();

            // إنشاء مصفوفة الأيام
            let days = '';

            // إضافة الأيام الفارغة قبل اليوم الأول من الشهر
            for (let i = 0; i < startingDay; i++) {
                days += '<div class="calendar-day empty"></div>';
            }

            // إضافة أيام الشهر
            const today = new Date();
            for (let i = 1; i <= totalDays; i++) {
                const isToday = today.getDate() === i && today.getMonth() === month && today.getFullYear() === year;
                const isSelected = issueDateSelectedDate && issueDateSelectedDate.getDate() === i && issueDateSelectedDate.getMonth() === month && issueDateSelectedDate.getFullYear() === year;

                const dayClass = isToday ? 'calendar-day today' : 'calendar-day';
                const selectedClass = isSelected ? ' selected' : '';

                days += `<div class="${dayClass}${selectedClass}" data-date="${i}">${i}</div>`;
            }

            // عرض الأيام في التقويم
            issueDateCalendarDays.innerHTML = days;

            // إضافة مستمعي الأحداث للأيام
            const dayElements = issueDateCalendarDays.querySelectorAll('.calendar-day:not(.empty)');
            dayElements.forEach(dayElement => {
                dayElement.addEventListener('click', function() {
                    // إزالة التحديد السابق
                    const selectedDays = issueDateCalendarDays.querySelectorAll('.calendar-day.selected');
                    selectedDays.forEach(day => day.classList.remove('selected'));

                    // تحديد اليوم الجديد
                    dayElement.classList.add('selected');

                    // تحديث التاريخ المحدد
                    const day = parseInt(dayElement.getAttribute('data-date'));
                    issueDateSelectedDate = new Date(year, month, day);

                    // تحديث حقل التاريخ
                    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    issueDateInput.value = formattedDate;

                    // تحديث العرض
                    const displayFormat = `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
                    issueDateDisplay.value = displayFormat;

                    // إغلاق التقويم
                    issueDatePicker.style.display = 'none';
                });
            });
        }

        // تعديل نموذج إضافة الفاتورة ليستخدم AJAX
        $('#addInvoiceForm').on('submit', function(e) {
            e.preventDefault();

            // عرض مؤشر التحميل
            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.text();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...');
            submitBtn.prop('disabled', true);

            // إرسال البيانات باستخدام AJAX
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودال
                    $('#addInvoiceModal').modal('hide');

                    // إعادة تعيين النموذج
                    $('#addInvoiceForm')[0].reset();

                    // عرض رسالة نجاح
                    toastr.success('تم إضافة الفاتورة بنجاح');

                    // تحديث إحصائيات الفواتير (إذا كانت موجودة)
                    updateInvoiceStats();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) { // خطأ في التحقق من البيانات
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(errors[field][0]);
                        }
                    } else {
                        toastr.error('حدث خطأ أثناء إضافة الفاتورة');
                    }
                },
                complete: function() {
                    // إعادة زر الإرسال إلى حالته الأصلية
                    submitBtn.html(originalBtnText);
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // دالة لتحديث إحصائيات الفواتير
        function updateInvoiceStats() {
            // تحديث إحصائيات الفواتير في لوحة التحكم
            $.ajax({
                url: "{{ route('dashboard') }}",
                method: "GET",
                dataType: "json",
                data: { get_stats: true },
                success: function(stats) {
                    // تحديث الإحصائيات إذا كانت موجودة
                    if (stats.invoicesCount !== undefined) {
                        $('.invoice-number:eq(0)').text(stats.invoicesCount);
                    }
                    if (stats.totalAmount !== undefined) {
                        $('.invoice-number:eq(1)').text(formatLargeNumber(stats.totalAmount));
                    }
                    if (stats.paidAmount !== undefined) {
                        $('.invoice-number:eq(2)').text(formatLargeNumber(stats.paidAmount));
                    }
                    if (stats.remainingAmount !== undefined) {
                        $('.invoice-number:eq(3)').text(formatLargeNumber(stats.remainingAmount));
                    }
                }
            });
        }

        // البحث عن المريض
        $('#patient_search_invoice').on('input', function() {
            const searchTerm = $(this).val().trim();
            if (searchTerm.length > 1) {
                $.ajax({
                    url: "{{ route('patients.search') }}",
                    method: "GET",
                    data: { term: searchTerm },
                    success: function(response) {
                        const patientSelect = $('#patient_id_invoice');
                        patientSelect.empty();
                        patientSelect.append('<option value="" selected disabled>اختر المريض</option>');

                        if (response.length > 0) {
                            response.forEach(patient => {
                                patientSelect.append(`<option value="${patient.id}">${patient.full_name}</option>`);
                            });
                        } else {
                            patientSelect.append('<option value="" disabled>لا توجد نتائج</option>');
                        }
                    }
                });
            }
        });
    });

    // دالة لتنسيق الأرقام الكبيرة
    function formatLargeNumber(number) {
        if (number >= 1000000) {
            return Math.round(number / 1000000);
        } else if (number >= 1000) {
            return Math.round(number / 1000);
        }
        return number;
    }

    // تفعيل البحث عن المريض في مودل إضافة موعد
    $(document).ready(function() {
        const patientSearchInput = $('#patient_search');
        const patientSelect = $('#patient_id');
        let searchTimeout;

        // إنشاء عنصر لعرض نتائج البحث
        const searchResultsContainer = $('<div class="search-results-container"></div>');
        patientSearchInput.after(searchResultsContainer);
        searchResultsContainer.hide();

        // تنسيق عنصر نتائج البحث
        searchResultsContainer.css({
            'position': 'absolute',
            'width': '100%',
            'max-height': '200px',
            'overflow-y': 'auto',
            'background-color': '#fff',
            'border': '1px solid #ddd',
            'border-radius': '0 0 5px 5px',
            'z-index': '1000',
            'box-shadow': '0 4px 8px rgba(0,0,0,0.1)',
            'margin-top': '2px'
        });

        // إضافة تنسيق CSS للنتائج
        $('head').append(`
            <style>
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
        `);

        // عند كتابة نص في حقل البحث
        patientSearchInput.on('input', function() {
            const searchTerm = $(this).val().trim();

            // إلغاء البحث السابق إذا كان هناك
            clearTimeout(searchTimeout);

            // إخفاء نتائج البحث إذا كان حقل البحث فارغًا
            if (searchTerm === '') {
                searchResultsContainer.hide();
                return;
            }

            // تأخير البحث لتجنب الطلبات المتكررة
            searchTimeout = setTimeout(function() {
                // عرض مؤشر التحميل
                searchResultsContainer.html('<div class="search-result-item loading"><i class="fas fa-spinner fa-spin"></i> جاري البحث...</div>');
                searchResultsContainer.show();

                // طلب البحث من الخادم
                $.ajax({
                    url: "{{ route('patients.search-json') }}",
                    method: 'GET',
                    data: { query: searchTerm },
                    dataType: 'json',
                    success: function(patients) {
                        // تفريغ نتائج البحث
                        searchResultsContainer.empty();

                        // إذا لم تكن هناك نتائج
                        if (!patients || patients.length === 0) {
                            searchResultsContainer.append('<div class="search-result-item no-results">لا توجد نتائج</div>');
                        } else {
                            // إضافة كل مريض إلى نتائج البحث
                            patients.forEach(function(patient) {
                                const resultItem = $(`
                                    <div class="search-result-item" data-id="${patient.id}" data-name="${patient.full_name}">
                                        <div class="patient-search-avatar">
                                            <img src="${patient.gender === 'female' ? '{{ asset("images/22.png") }}' : '{{ asset("images/11.png") }}'}" alt="${patient.full_name}">
                                        </div>
                                        <div class="patient-search-info">
                                            <div class="patient-search-name">${patient.full_name}</div>
                                            <div class="patient-search-details">${patient.age} سنة | ${patient.phone_number}</div>
                                        </div>
                                    </div>
                                `);

                                searchResultsContainer.append(resultItem);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('خطأ في البحث:', status, error);
                        console.log('استجابة الخطأ:', xhr.responseText);

                        searchResultsContainer.empty();
                        searchResultsContainer.append('<div class="search-result-item error">حدث خطأ أثناء البحث</div>');
                    }
                });
            }, 300);
        });

        // عند النقر على نتيجة بحث
        $(document).on('click', '.search-result-item', function() {
            if ($(this).hasClass('no-results') || $(this).hasClass('error') || $(this).hasClass('loading')) {
                return;
            }

            const patientId = $(this).data('id');
            const patientName = $(this).data('name');

            // تحديث حقل الاختيار بالمريض المحدد
            patientSelect.val(patientId);
            patientSelect.trigger('change'); // تشغيل حدث التغيير لتحديث أي عناصر مرتبطة

            // تحديث حقل البحث بالاسم المحدد
            patientSearchInput.val(patientName);

            // إخفاء نتائج البحث
            searchResultsContainer.hide();
        });

        // إخفاء نتائج البحث عند النقر خارجها
        $(document).on('click', function(e) {
            if (!patientSearchInput.is(e.target) && !searchResultsContainer.is(e.target) && searchResultsContainer.has(e.target).length === 0) {
                searchResultsContainer.hide();
            }
        });
    });

    // إضافة هذا الكود في قسم scripts في نهاية الصفحة
    $(document).ready(function() {
        // تفعيل زر تعديل الموعد
        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();

            const appointmentId = $(this).data('id');
            const url = $(this).attr('href');

            // عرض مؤشر التحميل
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');

            // جلب بيانات الموعد
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const appointment = response.appointment;

                        // تعبئة النموذج ببيانات الموعد
                        $('#edit_appointment_id').val(appointment.id);
                        $('#edit_patient_id').val(appointment.patient_id);
                        $('#edit_patient_search').val(appointment.patient.full_name);

                        // تنسيق التاريخ والوقت
                        const appointmentDate = new Date(appointment.appointment_date);
                        const formattedDate = `${String(appointmentDate.getDate()).padStart(2, '0')}/${String(appointmentDate.getMonth() + 1).padStart(2, '0')}/${appointmentDate.getFullYear()}`;
                        $('#edit_date_display').val(formattedDate);
                        $('#edit_appointment_date').val(appointment.appointment_date);

                        // تنسيق الوقت
                        const appointmentTime = appointment.appointment_time;
                        const timeParts = appointmentTime.split(':');
                        const hour = parseInt(timeParts[0]);
                        const minute = timeParts[1];
                        const ampm = hour >= 12 ? 'PM' : 'AM';
                        const hour12 = hour % 12 || 12;
                        const formattedTime = `${hour12}:${minute} ${ampm}`;

                        $('#edit_time_display').val(formattedTime);
                        $('#edit_appointment_time').val(appointmentTime);

                        // تعبئة باقي الحقول
                        $('#edit_session_type').val(appointment.session_type);
                        $('#edit_amount').val(appointment.amount);
                        $('#edit_note').val(appointment.note);
                        $('#edit_session_title').val(appointment.session_title);
                        $('#edit_status').val(appointment.status || 'pending');

                        // تحديث عنوان النموذج
                        $('#editAppointmentForm').attr('action', `/appointments/${appointment.id}`);

                        // فتح المودال
                        $('#editAppointmentModal').modal('show');
                    }
                },
                error: function(xhr) {
                    toastr.error('حدث خطأ أثناء تحميل بيانات الموعد');
                    console.error(xhr.responseText);
                },
                complete: function() {
                    // إعادة أيقونة التعديل
                    $('.edit-btn[data-id="' + appointmentId + '"]').html('<i class="fas fa-edit"></i>');
                }
            });
        });

        // معالجة تقديم نموذج تعديل الموعد
        $('#editAppointmentForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const url = form.attr('action');
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.text();

            // عرض مؤشر التحميل
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> جاري التحديث...');
            submitBtn.prop('disabled', true);

            // إرسال البيانات
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // إغلاق المودال
                    $('#editAppointmentModal').modal('hide');

                    // عرض رسالة نجاح
                    toastr.success('تم تحديث الموعد بنجاح');

                    // تحديث قائمة المواعيد
                    refreshTodayAppointments();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ
                    if (xhr.status === 422) { // خطأ في التحقق من البيانات
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(errors[field][0]);
                        }
                    } else {
                        toastr.error('حدث خطأ أثناء تحديث الموعد');
                    }
                },
                complete: function() {
                    // إعادة زر الإرسال إلى حالته الأصلية
                    submitBtn.html(originalBtnText);
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // تفعيل حقول التاريخ والوقت في نموذج التعديل
        // (يمكن نسخ الكود الموجود في نموذج الإضافة وتعديله ليناسب نموذج التعديل)
    });

    // إضافة هذا الكود في قسم scripts في نهاية الصفحة
    $(document).ready(function() {
        // تفعيل زر حذف الموعد
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            const appointmentId = $(this).data('id');
            const deleteUrl = `/appointments/${appointmentId}`;
            const row = $(this).closest('tr'); // الصف الذي يحتوي على الموعد

            // تأكيد الحذف
            if (confirm('هل أنت متأكد من حذف هذا الموعد؟')) {
                // عرض مؤشر التحميل
                $(this).html('<i class="fas fa-spinner fa-spin"></i>');

                // إرسال طلب الحذف
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // عرض رسالة نجاح
                        toastr.success('تم حذف الموعد بنجاح');

                        // حذف الصف من الجدول مباشرة بتأثير بصري
                        row.fadeOut(400, function() {
                            // تحديث عداد المواعيد في الإحصائيات
                            updateAppointmentStats();

                            // حذف الصف من DOM
                            $(this).remove();

                            // التحقق مما إذا كان الجدول فارغاً الآن
                            if ($('.appointments-table tbody tr').length === 0) {
                                $('.appointments-table tbody').append('<tr><td colspan="8" class="text-center">لا توجد مواعيد لليوم</td></tr>');
                            }

                            // إعادة ترقيم الصفوف
                            $('.appointments-table tbody tr').each(function(index) {
                                $(this).find('td:first').text(String(index + 1).padStart(2, '0'));
                            });
                        });
                    },
                    error: function(xhr) {
                        // عرض رسالة خطأ
                        toastr.error('حدث خطأ أثناء حذف الموعد');
                        console.error(xhr.responseText);

                        // إعادة أيقونة الحذف
                        $(`.delete-btn[data-id="${appointmentId}"]`).html('<i class="fas fa-trash-alt"></i>');
                    }
                });
            }
        });
    });

    // دالة لتحديث إحصائيات المواعيد
    function updateAppointmentStats() {
        $.ajax({
            url: "{{ route('dashboard') }}",
            method: "GET",
            dataType: "json",
            data: { get_stats: true },
            success: function(stats) {
                // تحديث عدد مواعيد اليوم في الإحصائيات
                $('.stats-number:first').text(stats.todayAppointmentsCount);
            }
        });
    }
</script>
@endsection

@extends('layouts.patient')

@section('title', 'الملف الشخصي للمريض')

@section('content')
<div class="patient-profile-container">
    <div class="profile-column">
        <!-- بطاقة معلومات المريض مع الصورة -->
        <div class="patient-card">
            <div class="patient-avatar">
                <img src="{{ asset($patient->gender == 'female' ? 'images/22.png' : 'images/11.png') }}" alt="{{ $patient->full_name }}">
            </div>
            <div class="patient-name">{{ $patient->full_name }}</div>
            <div class="patient-age">{{ $patient->age }} سنة</div>
            <div class="patient-actions">
                <a href="#" class="edit-photo">تعديل الصورة</a>
                <a href="#" class="remove-photo">إزالة الصور</a>
            </div>
        </div>

        <!-- بطاقة معلومات المريض التفصيلية -->
        <div class="patient-details-card">

            <div class="detail-row">
                <div class="detail-label">اسم المريض الكامل:</div>
                <div class="detail-value">{{ $patient->full_name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">العمـــر:</div>
                <div class="detail-value">{{ $patient->age }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">الجنـس:</div>
                <div class="detail-value">{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">رقم الهاتف:</div>
                <div class="detail-value">{{ $patient->phone_number }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">الوظيفة:</div>
                <div class="detail-value">{{ $patient->occupation ?: 'UI / UX Designer' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">رقم السجل الطبي:</div>
                <div class="detail-value">{{ $patient->patient_number }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">تاريخ الإضافة:</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($patient->registration_date)->format('Y.m.d') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">وقت الإضافة:</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($patient->registration_time)->format('h:i A') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">عدد الزيارات الكلي:</div>
                <div class="detail-value">{{ $patient->appointments ? $patient->appointments->count() : '0' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">تاريخ آخر زيارة:</div>
                <div class="detail-value">{{ $patient->appointments && $patient->appointments->count() > 0 ? \Carbon\Carbon::parse($patient->appointments->sortByDesc('appointment_date')->first()->appointment_date)->format('Y.m.d') : '2024.10.10' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">الحالة الحالية:</div>
                <div class="detail-value">مستمرة</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">الأمراض المزمنة:</div>
                <div class="detail-value">{{ $patient->chronicDiseases->count() > 0 ? $patient->chronicDiseases->pluck('name')->implode(', ') : 'لا يوجد' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">الحساسية:</div>
                <div class="detail-value">{{ $patient->allergies->count() > 0 ? $patient->allergies->pluck('name')->implode(', ') : 'لا يوجد' }}</div>
            </div>

            <button class="edit-info-btn">تعديل المعلومات</button>
        </div>
    </div>

    <div class="appointments-column">
        <!-- قائمة آخر المواعيد -->
        <div class="appointments-card">
            <h3 class="appointments-title">آخــر المواعيد</h3>
            <div class="appointments-table-container">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>منذ</th>
                            <th>التاريــخ</th>
                            <th>وقت الموعد</th>
                            <th>عنوان الموعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // التأكد من تحميل المواعيد مباشرة من قاعدة البيانات
                            $patientAppointments = \App\Models\Appointment::where('patient_id', $patient->id)
                                ->orderBy('appointment_date', 'desc')
                                ->take(8)
                                ->get();
                        @endphp

                        @if($patientAppointments->count() > 0)
                            @foreach($patientAppointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->diffForHumans() }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y/m/d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td class="appointment-title-cell">
                                    <span class="appointment-title">
                                        <i class="fas fa-{{ $appointment->status == 'completed' ? 'check' : 'lock' }}"></i>
                                        {{ $appointment->session_type ?: 'موعد عام' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">لا توجد مواعيد سابقة لهذا المريض</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="add-appointment-container">
                <a href="#" class="add-appointment-btn" id="openAddAppointmentModal">
                    <i class="fas fa-plus"></i>
                    إضافة موعد
                </a>
            </div>
        </div>

        <div class="cards-row">
            <!-- قائمة الحساسية -->
            <div class="allergies-card" style="width: 776px;">
                <div class="allergies-header">
                    <h3 class="allergies-title">الحساسية</h3>
                    <a href="#" class="add-allergy-btn" data-toggle="modal" data-target="#addAllergyModal">إضافــة</a>
                </div>
                <div class="allergies-table-container">
                    <table class="allergies-table">
                        <thead>
                            <tr>
                                <th>ت</th>
                                <th>اسم الدواء</th>
                                <th>مستوى الخطورة</th>
                                <th>ملاحظات</th>
                                <th>تعديل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($patient->allergies->count() > 0)
                                @foreach($patient->allergies as $index => $allergy)
                                <tr>
                                    <td class="allergy-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $allergy->name }}</td>
                                    <td>{{ $allergy->pivot->severity ?? 'عادي' }}</td>
                                    <td>{{ $allergy->pivot->notes ?? '—' }}</td>
                                    <td class="actions-cell">
                                        <a href="#" class="allergy-delete-btn" data-allergy-id="{{ $allergy->id }}">
                                            <i class="fas fa-trash-alt" style="color: #e74c3c;"></i>
                                        </a>
                                        <a href="#" class="allergy-edit-btn" data-allergy-id="{{ $allergy->id }}"
                                           data-allergy-name="{{ $allergy->name }}"
                                           data-allergy-severity="{{ $allergy->pivot->severity ?? 'منخفض' }}"
                                           data-allergy-notes="{{ $allergy->pivot->notes ?? '' }}">
                                            <i class="fas fa-pen" style="color: #3498db;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد حساسيات مسجلة لهذا المريض</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="cards-row" style="margin-top: 20px;">
            <!-- قائمة الملاحظات -->
            <div class="notes-card" style="width: 776px;">
                <div class="notes-header">
                    <h3 class="notes-title">مُلاحظات</h3>
                    <a href="#" class="add-note-btn" data-toggle="modal" data-target="#addNoteModal">إضافــة</a>
                </div>
                <div class="notes-list">
                    @if(is_object($patient->patientNotes) && $patient->patientNotes->count() > 0)
                        @foreach($patient->patientNotes as $index => $note)
                        <div class="note-item {{ $note->is_important ? 'important-note' : '' }}">
                            <div class="note-content">
                                <div class="note-title">{{ $note->title }}</div>
                                <div class="note-date">{{ \Carbon\Carbon::parse($note->created_at)->format('Y/m/d') }}</div>
                            </div>
                            <div class="note-actions">
                                <a href="#" class="note-delete-btn" data-note-id="{{ $note->id }}">
                                    <i class="fas fa-trash-alt" style="color: #e74c3c;"></i>
                                </a>
                                <a href="#" class="note-edit-btn" data-note-id="{{ $note->id }}"
                                   data-note-title="{{ $note->title }}"
                                   data-note-content="{{ $note->content }}"
                                   data-note-important="{{ $note->is_important ? 'true' : 'false' }}">
                                    <i class="fas fa-pen" style="color: #3498db;"></i>
                                </a>
                                <span class="note-number">{{ $index + 1 }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-notes">
                            <p>لا توجد ملاحظات مسجلة لهذا المريض</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودل إضافة الحساسية -->
<div class="modal fade" id="addAllergyModal" tabindex="-1" role="dialog" aria-labelledby="addAllergyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="addAllergyModalLabel">إضافة حساسية جديدة</h5>
            </div>
            <div class="modal-body">
                <form id="addAllergyForm" action="{{ route('allergies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="allergy_name">اسم الدواء أو المادة</label>
                        <input type="text" class="form-control" id="allergy_name" name="name" placeholder="اكتب اسم الدواء أو المادة" list="allergy-suggestions" required>
                        <datalist id="allergy-suggestions">
                            <option value="البنسلين">
                            <option value="الأسبرين">
                            <option value="المضادات الحيوية">
                            <option value="اللاتكس">
                            <option value="المكسرات">
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="severity">مستوى الخطورة</label>
                        <div class="input-with-icon">
                            <select class="form-control custom-select" id="severity" name="severity">
                                <option value="" disabled selected>اضغط لاختياره</option>
                                <option value="منخفض">منخفض</option>
                                <option value="متوسط">متوسط</option>
                                <option value="عالي">عالي</option>
                                <option value="خطير">خطير</option>
                            </select>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">ملاحظات</label>
                        <input type="text" class="form-control" id="notes" name="notes" placeholder="اكتب الملاحظات">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">إضافة الحساسية</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل تعديل الحساسية -->
<div class="modal fade" id="editAllergyModal" tabindex="-1" role="dialog" aria-labelledby="editAllergyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="editAllergyModalLabel">تعديل الحساسية</h5>
            </div>
            <div class="modal-body">
                <form id="editAllergyForm" action="{{ route('allergies.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="hidden" name="allergy_id" id="edit_allergy_id">

                    <div class="form-group">
                        <label for="edit_allergy_name">اسم الدواء أو المادة</label>
                        <input type="text" class="form-control" id="edit_allergy_name" name="name" placeholder="اكتب اسم الدواء أو المادة" list="allergy-suggestions" required>
                        <datalist id="allergy-suggestions">
                            <option value="البنسلين">
                            <option value="الأسبرين">
                            <option value="المضادات الحيوية">
                            <option value="اللاتكس">
                            <option value="المكسرات">
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="edit_severity">مستوى الخطورة</label>
                        <div class="input-with-icon">
                            <select class="form-control custom-select" id="edit_severity" name="severity">
                                <option value="منخفض">منخفض</option>
                                <option value="متوسط">متوسط</option>
                                <option value="عالي">عالي</option>
                                <option value="خطير">خطير</option>
                            </select>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_notes">ملاحظات</label>
                        <input type="text" class="form-control" id="edit_notes" name="notes" placeholder="اكتب الملاحظات">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل تأكيد حذف الحساسية -->
<div class="modal fade" id="deleteAllergyModal" tabindex="-1" role="dialog" aria-labelledby="deleteAllergyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAllergyModalLabel">تأكيد حذف الحساسية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف هذه الحساسية؟</p>
                <form id="deleteAllergyForm" action="{{ route('allergies.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    <input type="hidden" name="allergy_id" id="delete_allergy_id">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل إضافة موعد جديد -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="addAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="addAppointmentModalLabel">إضافة موعد جديد</h5>
            </div>
            <div class="modal-body">
                <form id="addAppointmentForm" action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="patient_name">اسم المريض</label>
                        <div class="input-with-icon">
                            <select class="form-control custom-select" id="patient_name" disabled>
                                <option value="{{ $patient->id }}" selected>{{ $patient->full_name }}</option>
                            </select>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">تاريخ الموعد</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control" id="appointment_date" name="appointment_date" placeholder="اضغط لاختياره" readonly required>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="appointment_time">وقت الموعد</label>
                        <div class="input-with-icon">
                            <input type="text" class="form-control" id="appointment_time" name="appointment_time" placeholder="اضغط لاختياره" readonly required>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- إضافة حقل نوع الجلسة في مودل إضافة موعد -->
                    <div class="form-group">
                        <label for="session_type">نوع الجلسة</label>
                        <div class="input-with-icon">
                            <select class="form-control custom-select" id="session_type" name="session_type" required>
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
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <!-- قائمة الأوقات المتاحة -->
                    <div class="available-times-container" id="availableTimesContainer" style="display: none;">
                        <h5 class="available-times-title">الأوقات المتوفرة</h5>
                        <div class="available-times-grid">
                            <div class="time-slot" data-time="09:00">9:00 AM</div>
                            <div class="time-slot" data-time="09:30">9:30 AM</div>
                            <div class="time-slot" data-time="10:00">10:00 AM</div>
                            <div class="time-slot" data-time="10:30">10:30 AM</div>
                            <div class="time-slot" data-time="11:00">11:00 AM</div>
                            <div class="time-slot" data-time="11:30">11:30 AM</div>
                            <div class="time-slot" data-time="12:00">12:00 PM</div>
                            <div class="time-slot" data-time="12:30">12:30 PM</div>
                            <div class="time-slot" data-time="13:00">1:00 PM</div>
                            <div class="time-slot" data-time="13:30">1:30 PM</div>
                            <div class="time-slot" data-time="14:00">2:00 PM</div>
                            <div class="time-slot" data-time="14:30">2:30 PM</div>
                            <div class="time-slot" data-time="15:00">3:00 PM</div>
                            <div class="time-slot" data-time="15:30">3:30 PM</div>
                            <div class="time-slot" data-time="16:00">4:00 PM</div>
                            <div class="time-slot" data-time="16:30">4:30 PM</div>
                            <div class="time-slot" data-time="17:00">5:00 PM</div>
                            <div class="time-slot" data-time="17:30">5:30 PM</div>
                            <div class="time-slot" data-time="18:00">6:00 PM</div>
                            <div class="time-slot" data-time="18:30">6:30 PM</div>
                            <div class="time-slot" data-time="19:00">7:00 PM</div>
                            <div class="time-slot" data-time="19:30">7:30 PM</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="amount">المبلغ</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="اكتب المبلغ">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">إضافة الموعد</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل إضافة ملاحظة جديدة -->
<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">إضافة ملاحظة جديدة</h5>
            </div>
            <div class="modal-body">
                <form id="addNoteForm" action="{{ route('patient-notes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="note_title">عنوان الملاحظة</label>
                        <input type="text" class="form-control" id="note_title" name="title" placeholder="اكتب عنوان الملاحظة" required>
                    </div>

                    <div class="form-group">
                        <label for="note_content">محتوى الملاحظة</label>
                        <textarea class="form-control" id="note_content" name="content" rows="4" placeholder="اكتب محتوى الملاحظة"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_important" name="is_important" value="1">
                            <label class="custom-control-label" for="is_important">ملاحظة مهمة</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">إضافة الملاحظة</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل تعديل الملاحظة -->
<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="editNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="editNoteModalLabel">تعديل الملاحظة</h5>
            </div>
            <div class="modal-body">
                <form id="editNoteForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="edit_note_title">عنوان الملاحظة</label>
                        <input type="text" class="form-control" id="edit_note_title" name="title" placeholder="اكتب عنوان الملاحظة" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_note_content">محتوى الملاحظة</label>
                        <textarea class="form-control" id="edit_note_content" name="content" rows="4" placeholder="اكتب محتوى الملاحظة"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_is_important" name="is_important" value="1">
                            <label class="custom-control-label" for="edit_is_important">ملاحظة مهمة</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- مودل تعديل معلومات المريض -->
<div class="modal fade" id="editPatientDetailsModal" tabindex="-1" role="dialog" aria-labelledby="editPatientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content new-design">
            <div class="modal-header">
                <h5 class="modal-title" id="editPatientDetailsModalLabel">تعديل معلومات المريض</h5>
            </div>
            <div class="modal-body">
                <form id="editPatientDetailsForm" action="{{ route('patients.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="full_name">اسم المريض الكامل</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $patient->full_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="age">العمر</label>
                                <input type="number" class="form-control" id="age" name="age" value="{{ $patient->age }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">الجنس</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="male" {{ $patient->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ $patient->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">رقم الهاتف</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $patient->phone_number }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="occupation">المهنة</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="{{ $patient->occupation }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">العنوان</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $patient->address }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $patient->notes }}</textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .patient-profile-container {
        padding: 20px;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        margin-right: 20px;
        gap: 20px;
    }

    .profile-column {
        display: flex;
        flex-direction: column;
    }

    .appointments-column {
        flex: 0 0 auto;
        width: 776px;
    }

    .cards-row {
        display: flex;
        gap: 20px;
    }

    /* بطاقة معلومات المريض مع الصورة */
    .patient-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 20px;
        text-align: center;
        width: 368px;
    }

    .patient-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 15px;
        background-color: #cfe2f3;
    }

    .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .patient-name {
        font-size: 24px;
        font-weight: bold;
        color: #1e5a7e;
        margin-bottom: 5px;
    }

    .patient-age {
        font-size: 16px;
        color: #777;
        margin-bottom: 20px;
    }

    .patient-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .edit-photo {
        color: #3498db;
        text-decoration: none;
    }

    .remove-photo {
        color: #e74c3c;
        text-decoration: none;
    }

    /* بطاقة معلومات المريض التفصيلية */
    .patient-details-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        width: 368px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }

    .card-title {
        color: #1e5a7e;
        font-size: 18px;
        margin: 0;
    }

    .edit-details-btn {
        display: flex;
        align-items: center;
        color: #3498db;
        text-decoration: none;
        font-size: 14px;
        gap: 5px;
    }

    .edit-details-btn:hover {
        color: #2980b9;
        text-decoration: none;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-label {
        color: #1e5a7e;
        font-weight: 600;
        text-align: right;
        width: 150px;
    }

    .detail-value {
        color: #333;
        text-align: right;
        width: 150px;
    }

    .edit-info-btn {
        background-color: #1e5a7e;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 12px;
        width: 100%;
        margin-top: 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
    }

    .edit-info-btn:hover {
        background-color: #174a66;
    }

    /* قائمة آخر المواعيد */
    .appointments-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        width: 776px;
        margin-bottom: 20px;
    }

    .appointments-title {
        color: #1e5a7e;
        font-size: 20px;
        margin-top: 0;
        margin-bottom: 20px;
        text-align: right;
    }

    .appointments-table-container {
        overflow-x: auto;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .appointments-table th {
        color: #1e5a7e;
        font-weight: 600;
        padding: 12px 15px;
        text-align: right;
        border-bottom: 1px solid #eee;
    }

    .appointments-table td {
        padding: 12px 15px;
        text-align: right;
        border-bottom: 1px solid #f0f0f0;
    }

    .appointment-title-cell {
        position: relative;
    }

    .appointment-title {
        display: flex;
        align-items: center;
        color: #1e5a7e;
    }

    .appointment-title i {
        margin-left: 8px;
        color: #1e5a7e;
    }

    .add-appointment-container {
        margin-top: 20px;
        text-align: center;
    }

    .add-appointment-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        background-color: transparent;
        border: 1px dashed #1e5a7e;
        border-radius: 5px;
        color: #1e5a7e;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
    }

    .add-appointment-btn i {
        margin-left: 8px;
    }

    /* قائمة الحساسية */
    .allergies-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        width: 378px;
    }

    .allergies-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .allergies-title {
        color: #e74c3c;
        font-size: 20px;
        margin: 0;
        text-align: right;
    }

    .add-allergy-btn {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 15px;
        text-decoration: none;
        font-size: 14px;
    }

    .allergies-table-container {
        overflow-x: auto;
    }

    .allergies-table {
        width: 100%;
        border-collapse: collapse;
    }

    .allergies-table th {
        color: #555;
        font-weight: 600;
        padding: 12px 15px;
        text-align: right;
        border-bottom: 1px solid #eee;
    }

    .allergies-table td {
        padding: 12px 15px;
        text-align: right;
        border-bottom: 1px solid #f0f0f0;
    }

    .allergy-number {
        color: #777;
    }

    .actions-cell {
        white-space: nowrap;
        text-align: center;
    }

    .allergy-delete-btn, .allergy-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .allergy-delete-btn:hover, .allergy-edit-btn:hover {
        background-color: #f1f5f9;
    }

    .allergy-delete-btn i {
        color: #e74c3c;
        font-size: 16px;
    }

    .allergy-edit-btn i {
        color: #3498db;
        font-size: 16px;
    }

    /* قائمة الملاحظات */
    .notes-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        width: 776px;
    }

    .notes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 0 15px;
    }

    .notes-title {
        color: #1e5a7e;
        font-size: 20px;
        margin: 0;
        text-align: right;
    }

    .add-note-btn {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 5px 15px;
        text-decoration: none;
        font-size: 14px;
    }

    .notes-list {
        display: flex;
        flex-direction: column;
    }

    .note-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .note-content {
        flex: 1;
        text-align: right;
        padding-left: 50px;
    }

    .note-title {
        font-weight: 600;
        color: #1e5a7e;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .note-date {
        color: #718096;
        font-size: 14px;
    }

    .note-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .note-delete-btn, .note-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: background-color 0.3s ease;
    }

    .note-delete-btn:hover, .note-edit-btn:hover {
        background-color: #f1f5f9;
    }

    .note-delete-btn i {
        color: #e74c3c;
        font-size: 16px;
    }

    .note-edit-btn i {
        color: #3498db;
        font-size: 16px;
    }

    .note-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background-color: #e2e8f0;
        color: #4a5568;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
    }

    .important-note {
        border-right: 4px solid #e53e3e;
    }

    .empty-notes {
        text-align: center;
        padding: 20px;
        color: #718096;
    }

    /* تعديل موضع المحتوى ليكون بالقرب من السلايدر */
    .content-area {
        display: flex;
        justify-content: flex-start;
        padding-right: 0;
    }

    /* تنسيق المودل */
    .modal {
        direction: rtl;
    }

    .modal-backdrop {
        opacity: 0.5;
    }

    .modal-dialog {
        margin: 1.75rem auto;
        max-width: 500px;
    }

    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .modal-header {
        background-color: #1e5a7e;
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        direction: rtl;
    }

    .modal-title {
        font-weight: 600;
        margin: 0;
    }

    .close {
        color: white;
        opacity: 0.8;
        font-size: 24px;
        margin: -15px -10px -15px auto;
    }

    .close:hover {
        color: white;
        opacity: 1;
    }

    .modal-body {
        padding: 20px;
        direction: rtl;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: right;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1e5a7e;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #1e5a7e;
        outline: none;
        box-shadow: 0 0 0 2px rgba(30, 90, 126, 0.2);
    }

    .modal-footer {
        border-top: 1px solid #eee;
        padding: 15px 20px;
        display: flex;
        justify-content: flex-start;
    }

    .btn {
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        margin-left: 10px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-primary {
        background-color: #1e5a7e;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #174a66;
    }

    /* تصميم المودل الجديد */
    .modal-content.new-design {
        background-color: #f5f8fb;
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-content.new-design .modal-header {
        background-color: #f5f8fb;
        border-bottom: none;
        padding: 25px 25px 10px;
        text-align: center;
        display: block;
        border-radius: 15px 15px 0 0;
    }

    .modal-content.new-design .modal-title {
        color: #1e5a7e;
        font-size: 22px;
        font-weight: bold;
        width: 100%;
        text-align: center;
    }

    .modal-content.new-design .modal-body {
        padding: 10px 25px 20px;
    }

    .modal-content.new-design .form-group {
        margin-bottom: 20px;
    }

    .modal-content.new-design label {
        color: #1e5a7e;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .modal-content.new-design .form-control {
        background-color: #edf2f7;
        border: none;
        border-radius: 10px;
        padding: 12px 15px;
        color: #333;
        font-size: 14px;
        height: auto;
    }

    .modal-content.new-design .custom-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-color: #edf2f7;
        padding-right: 30px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #1e5a7e;
        pointer-events: none;
    }

    .modal-content.new-design .modal-footer {
        border-top: none;
        padding: 0 25px 25px;
        justify-content: center;
        flex-direction: column;
    }

    .modal-content.new-design .btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
    }

    .modal-content.new-design .btn-primary {
        background-color: #1e5a7e;
        border: none;
    }

    .modal-content.new-design .btn-secondary {
        background-color: transparent;
        border: 1px solid #ccc;
        color: #666;
    }

    .modal-content.new-design .btn-primary:hover {
        background-color: #174a66;
    }

    .modal-content.new-design .btn-secondary:hover {
        background-color: #f0f0f0;
        color: #333;
    }

    /* تعديل حجم المودل */
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 450px;
            margin: 1.75rem auto;
        }
    }

    /* تنسيق مخصص لتقويم Flatpickr */
    .flatpickr-calendar {
        direction: rtl;
        font-family: 'Tajawal', sans-serif;
        background: #f5f8fb;
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        width: 300px;
    }

    .flatpickr-months {
        background-color: #f5f8fb;
        color: #1e5a7e;
        padding: 10px 0;
        position: relative;
    }

    .flatpickr-month {
        background-color: #f5f8fb;
        color: #1e5a7e;
    }

    .flatpickr-current-month {
        font-size: 18px;
        font-weight: 600;
    }

    .flatpickr-weekday {
        background-color: #f5f8fb;
        color: #8a9aaf;
        font-size: 12px;
        font-weight: 600;
    }

    .flatpickr-day {
        border-radius: 50%;
        margin: 2px;
        height: 36px;
        line-height: 36px;
        color: #4a5568;
        font-weight: 500;
    }

    .flatpickr-day.selected {
        background-color: #1e9bd0;
        border-color: #1e9bd0;
        color: white;
    }

    .flatpickr-day:hover {
        background-color: #e2e8f0;
    }

    .flatpickr-day.today {
        border-color: #1e9bd0;
    }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        color: #1e5a7e;
        fill: #1e5a7e;
        padding: 10px;
    }

    .flatpickr-time {
        border-top: 1px solid #e2e8f0;
        background-color: #f5f8fb;
    }

    .flatpickr-time input {
        color: #4a5568;
        font-weight: 500;
    }

    .numInputWrapper:hover {
        background-color: #e2e8f0;
    }

    /* تنسيق حقول الإدخال */
    .modal-content.new-design .form-control[readonly] {
        background-color: #edf2f7;
        cursor: pointer;
    }

    /* تنسيق قائمة الأوقات المتاحة */
    .available-times-container {
        background-color: #f5f8fb;
        border-radius: 15px;
        padding: 15px;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .available-times-title {
        color: #1e5a7e;
        font-size: 18px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 15px;
    }

    .available-times-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        max-height: 300px;
        overflow-y: auto;
    }

    .time-slot {
        background-color: white;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        color: #4a5568;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .time-slot:hover {
        background-color: #e2e8f0;
    }

    .time-slot.selected {
        background-color: #1e9bd0;
        color: white;
    }

    .time-slot.disabled {
        background-color: #f1f1f1;
        color: #999;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .time-slot.disabled s {
        text-decoration: line-through;
    }

    .no-times-available {
        grid-column: 1 / -1;
        text-align: center;
        padding: 20px;
        color: #666;
        font-weight: 500;
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
<script>
    $(document).ready(function() {
        // التأكد من أن المودل مخفي عند تحميل الصفحة
        $('#addAllergyModal').modal({
            show: false
        });

        $('#editAllergyModal').modal({
            show: false
        });

        $('#deleteAllergyModal').modal({
            show: false
        });

        // تفعيل زر إضافة الحساسية لفتح المودل
        $('.add-allergy-btn').on('click', function(e) {
            e.preventDefault();
            $('#addAllergyModal').modal('show');
        });

        // تفعيل زر تعديل الحساسية
        $(document).on('click', '.allergy-edit-btn', function(e) {
            e.preventDefault();

            // استخراج بيانات الحساسية
            const allergyId = $(this).data('allergy-id');
            const allergyName = $(this).data('allergy-name');
            const allergySeverity = $(this).data('allergy-severity');
            const allergyNotes = $(this).data('allergy-notes');

            // تعبئة النموذج بالبيانات
            $('#edit_allergy_id').val(allergyId);
            $('#edit_allergy_name').val(allergyName);
            $('#edit_severity').val(allergySeverity);
            $('#edit_notes').val(allergyNotes);

            // عرض المودل
            $('#editAllergyModal').modal('show');
        });

        // تفعيل زر حذف الحساسية
        $(document).on('click', '.allergy-delete-btn', function(e) {
            e.preventDefault();

            if (!confirm('هل أنت متأكد من حذف هذه الحساسية؟')) {
                return;
            }

            const allergyId = $(this).data('allergy-id');

            // حذف الحساسية باستخدام AJAX
            $.ajax({
                url: "{{ route('allergies.destroy') }}",
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    patient_id: {{ $patient->id }},
                    allergy_id: allergyId
                },
                success: function(response) {
                    // تحديث الصفحة بعد الحذف
                    location.reload();
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء حذف الحساسية');
                    console.log(xhr.responseText);
                }
            });
        });

        // التعامل مع نموذج إضافة الحساسية
        $('#addAllergyForm').on('submit', function(e) {
            e.preventDefault();

            // استخدام AJAX لإرسال البيانات بدون تحديث الصفحة
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل بعد الإرسال الناجح
                    $('#addAllergyModal').modal('hide');

                    // تحديث الصفحة لعرض الحساسية الجديدة
                    location.reload();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ إذا كان هناك مشكلة
                    alert('حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.');
                    console.log(xhr.responseText);
                }
            });
        });

        // التعامل مع نموذج تعديل الحساسية
        $('#editAllergyForm').on('submit', function(e) {
            e.preventDefault();

            // استخدام AJAX لإرسال البيانات بدون تحديث الصفحة
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل بعد الإرسال الناجح
                    $('#editAllergyModal').modal('hide');

                    // تحديث الصفحة لعرض التغييرات
                    location.reload();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ إذا كان هناك مشكلة
                    alert('حدث خطأ أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.');
                    console.log(xhr.responseText);
                }
            });
        });

        // تهيئة مودل إضافة موعد
        $('#addAppointmentModal').modal({
            show: false
        });

        // تفعيل زر إضافة موعد
        $('#openAddAppointmentModal').on('click', function(e) {
            e.preventDefault();
            $('#addAppointmentModal').modal('show');
        });

        // تفعيل اختيار التاريخ بتنسيق مخصص
        flatpickr("#appointment_date", {
            dateFormat: "Y-m-d",
            locale: "ar",
            disableMobile: "true",
            monthSelectorType: "static",
            showMonths: 1,
            prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>',
            nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>',
            onChange: function(selectedDates, dateStr, instance) {
                // تحديث النص في حقل الإدخال ليكون بتنسيق ميلادي
                const date = new Date(dateStr);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                const formattedDate = `${year}/${month}/${day}`;
                instance.input.value = formattedDate;

                // جلب الأوقات المتاحة للتاريخ المختار
                fetchAvailableTimes(dateStr);
            }
        });

        // دالة لجلب الأوقات المتاحة من الخادم
        function fetchAvailableTimes(date) {
            // إظهار مؤشر التحميل
            $('#availableTimesContainer').html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> جاري تحميل الأوقات المتاحة...</div>');
            $('#availableTimesContainer').slideDown(200);

            // طلب الأوقات المتاحة من الخادم
            $.ajax({
                url: "{{ route('dashboard.available-times') }}",
                method: 'GET',
                data: {
                    date: date,
                    clinic_id: {{ $patient->dental_clinic_id ?? 1 }} // إرسال معرف العيادة
                },
                success: function(response) {
                    // إنشاء قائمة الأوقات المتاحة
                    let timesHtml = '<h5 class="available-times-title">الأوقات المتوفرة</h5>';
                    timesHtml += '<div class="available-times-grid">';

                    // التحقق من وجود أوقات متاحة
                    if (response.length === 0) {
                        timesHtml += '<div class="no-times-available">لا توجد أوقات متاحة في هذا اليوم</div>';
                    } else {
                        // إضافة كل وقت إلى القائمة
                        response.forEach(function(timeSlot) {
                            if (timeSlot.available) {
                                // وقت متاح
                                timesHtml += `<div class="time-slot" data-time="${timeSlot.time}">${timeSlot.formatted}</div>`;
                            } else {
                                // وقت محجوز
                                timesHtml += `<div class="time-slot disabled" data-time="${timeSlot.time}"><s>${timeSlot.formatted}</s></div>`;
                            }
                        });
                    }

                    timesHtml += '</div>';

                    // عرض قائمة الأوقات
                    $('#availableTimesContainer').html(timesHtml);

                    // تفعيل اختيار الوقت
                    $('.time-slot:not(.disabled)').on('click', function() {
                        const selectedTime = $(this).data('time');
                        const displayTime = $(this).text();

                        // تحديث حقل الإدخال بالوقت المختار
                        $('#appointment_time').val(displayTime);

                        // تخزين قيمة الوقت الفعلية في حقل مخفي
                        $('input[name="appointment_time_value"]').remove(); // إزالة أي حقل سابق
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'appointment_time_value',
                            value: selectedTime
                        }).appendTo('#addAppointmentForm');

                        // تحديث التنسيق
                        $('.time-slot').removeClass('selected');
                        $(this).addClass('selected');

                        // إخفاء القائمة بعد الاختيار
                        $('#availableTimesContainer').slideUp(200);
                    });
                },
                error: function(xhr) {
                    // عرض رسالة خطأ
                    $('#availableTimesContainer').html('<div class="text-center p-3 text-danger">حدث خطأ أثناء تحميل الأوقات المتاحة. يرجى المحاولة مرة أخرى.</div>');
                    console.log(xhr.responseText);
                }
            });
        }

        // إغلاق قائمة الأوقات عند النقر خارجها
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.form-group').length) {
                $('#availableTimesContainer').slideUp(200);
            }
        });

        // التعامل مع نموذج إضافة موعد
        $('#addAppointmentForm').on('submit', function(e) {
            e.preventDefault();

            // استخدام AJAX لإرسال البيانات بدون تحديث الصفحة
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل بعد الإرسال الناجح
                    $('#addAppointmentModal').modal('hide');

                    // تحديث الصفحة لعرض الموعد الجديد
                    location.reload();
                },
                error: function(xhr) {
                    // عرض رسائل الخطأ إذا كان هناك مشكلة
                    alert('حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.');
                    console.log(xhr.responseText);
                }
            });
        });

        // تفعيل مودل إضافة ملاحظة
        $('#addNoteModal').modal({
            show: false
        });

        // تفعيل مودل تعديل ملاحظة
        $('#editNoteModal').modal({
            show: false
        });

        // تفعيل زر إضافة ملاحظة
        $('.add-note-btn').on('click', function(e) {
            e.preventDefault();
            $('#addNoteModal').modal('show');
        });

        // تفعيل زر تعديل ملاحظة
        $(document).on('click', '.note-edit-btn', function(e) {
            e.preventDefault();

            // استخراج بيانات الملاحظة
            const noteId = $(this).data('note-id');
            const noteTitle = $(this).data('note-title');
            const noteContent = $(this).data('note-content');
            const isImportant = $(this).data('note-important') === 'true';

            // تعيين عنوان النموذج
            $('#editNoteForm').attr('action', `{{ url('/patient-notes') }}/${noteId}`);

            // تعبئة النموذج بالبيانات
            $('#edit_note_title').val(noteTitle);
            $('#edit_note_content').val(noteContent);
            $('#edit_is_important').prop('checked', isImportant);

            // عرض المودل
            $('#editNoteModal').modal('show');
        });

        // تفعيل زر حذف ملاحظة
        $(document).on('click', '.note-delete-btn', function(e) {
            e.preventDefault();

            if (!confirm('هل أنت متأكد من حذف هذه الملاحظة؟')) {
                return;
            }

            const noteId = $(this).data('note-id');

            // حذف الملاحظة باستخدام AJAX
            $.ajax({
                url: `{{ url('/patient-notes') }}/${noteId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // تحديث الصفحة بعد الحذف
                    location.reload();
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء حذف الملاحظة');
                    console.log(xhr.responseText);
                }
            });
        });

        // معالجة نموذج إضافة ملاحظة
        $('#addNoteForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل
                    $('#addNoteModal').modal('hide');

                    // تحديث الصفحة
                    location.reload();
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء إضافة الملاحظة');
                    console.log(xhr.responseText);
                }
            });
        });

        // معالجة نموذج تعديل ملاحظة
        $('#editNoteForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل
                    $('#editNoteModal').modal('hide');

                    // تحديث الصفحة
                    location.reload();
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء تعديل الملاحظة');
                    console.log(xhr.responseText);
                }
            });
        });

        // تفعيل مودل تعديل معلومات المريض
        $('#editPatientDetailsModal').modal({
            show: false
        });

        // تفعيل زر تعديل معلومات المريض
        $('.edit-info-btn').on('click', function(e) {
            e.preventDefault();
            $('#editPatientDetailsModal').modal('show');
        });

        // معالجة نموذج تعديل معلومات المريض
        $('#editPatientDetailsForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // إغلاق المودل
                    $('#editPatientDetailsModal').modal('hide');

                    // تحديث الصفحة
                    location.reload();
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء تحديث معلومات المريض');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection

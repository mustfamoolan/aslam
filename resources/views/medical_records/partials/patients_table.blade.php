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
        @forelse($patients as $patient)
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
        @empty
        <tr>
            <td colspan="7" class="text-center">لا توجد سجلات</td>
        </tr>
        @endforelse
    </tbody>
</table>

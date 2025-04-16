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
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    <a href="{{ route('appointments.edit', $appointment) }}" class="edit-btn">
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

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

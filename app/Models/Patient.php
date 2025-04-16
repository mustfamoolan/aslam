<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'dental_clinic_id',
        'full_name',
        'age',
        'gender',
        'phone_number',
        'occupation',
        'address',
        'patient_number',
        'registration_date',
        'registration_time',
        'notes',
        'is_starred',
        'is_archived',
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة
     *
     * @var array
     */
    protected $casts = [
        'registration_date' => 'date',
        'registration_time' => 'datetime',
        'is_starred' => 'boolean',
        'is_archived' => 'boolean',
    ];

    /**
     * العلاقة مع العيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }

    /**
     * العلاقة مع الأمراض المزمنة
     */
    public function chronicDiseases()
    {
        return $this->belongsToMany(ChronicDisease::class, 'patient_chronic_disease');
    }

    /**
     * العلاقة مع الحساسية
     */
    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'patient_allergy')
                    ->withPivot('severity', 'notes')
                    ->withTimestamps();
    }

    /**
     * علاقة المريض بالفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * العلاقة مع ملاحظات المريض
     */
    public function patientNotes()
    {
        return $this->hasMany(PatientNote::class);
    }

    /**
     * العلاقة مع صور حالة الأسنان
     */
    public function teethImages()
    {
        return $this->hasMany(PatientTeethImage::class);
    }

    /**
     * الحصول على صور حالة الأسنان قبل العلاج
     */
    public function beforeTeethImages()
    {
        return $this->teethImages()->where('type', 'before');
    }

    /**
     * الحصول على صور حالة الأسنان بعد العلاج
     */
    public function afterTeethImages()
    {
        return $this->teethImages()->where('type', 'after');
    }

    /**
     * العلاقة مع صور الأشعة
     */
    public function xrays()
    {
        return $this->hasMany(PatientXray::class);
    }

    /**
     * العلاقة مع المواعيد
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

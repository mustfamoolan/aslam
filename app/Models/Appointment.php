<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'appointment_date',
        'appointment_time',
        'amount',
        'session_type',
        'status',
        'is_starred',
        'is_archived',
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة
     *
     * @var array
     */
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'is_starred' => 'boolean',
        'is_archived' => 'boolean',
    ];

    /**
     * العلاقة مع المريض
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

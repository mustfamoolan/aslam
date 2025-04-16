<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'dental_clinic_id',
        'patient_id',
        'invoice_type',
        'issue_date',
        'amount',
        'paid_amount',
        'remaining_amount',
        'session_title',
        'note',
        'is_paid',
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع أخرى
     */
    protected $casts = [
        'issue_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    /**
     * علاقة الفاتورة بالعيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }

    /**
     * علاقة الفاتورة بالمريض
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

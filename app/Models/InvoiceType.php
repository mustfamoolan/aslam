<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'dental_clinic_id',
        'name',
    ];

    /**
     * علاقة نوع الفاتورة بالعيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }
}

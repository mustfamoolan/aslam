<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicNote extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'dental_clinic_id',
        'content',
        'is_important',
    ];

    /**
     * الحقول التي يجب معاملتها كقيم منطقية
     */
    protected $casts = [
        'is_important' => 'boolean',
    ];

    /**
     * علاقة مع العيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }
}

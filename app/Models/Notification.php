<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'dental_clinic_id',
        'title',
        'content',
        'type',
        'is_read',
        'read_at',
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع أخرى
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * علاقة التنبيه بالعيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }
}

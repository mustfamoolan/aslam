<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientXray extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'title',
        'image_path',
        'category',
        'notes',
        'xray_date',
        'is_starred',
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة
     *
     * @var array
     */
    protected $casts = [
        'xray_date' => 'date',
        'is_starred' => 'boolean',
    ];

    /**
     * العلاقة مع المريض
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * الحصول على المدة منذ إضافة الأشعة
     */
    public function getTimeSinceAttribute()
    {
        return Carbon::parse($this->xray_date)->diffForHumans();
    }
}

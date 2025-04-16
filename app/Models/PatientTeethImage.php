<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientTeethImage extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'image_path',
        'type',
        'notes',
        'image_date',
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة
     *
     * @var array
     */
    protected $casts = [
        'image_date' => 'date',
    ];

    /**
     * العلاقة مع المريض
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * الحصول على المدة منذ إضافة الصورة
     */
    public function getTimeSinceAttribute()
    {
        return Carbon::parse($this->image_date)->diffForHumans();
    }
}

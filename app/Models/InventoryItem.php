<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'dental_clinic_id',
        'name',
        'quantity',
        'status',
        'expiry_date',
    ];

    /**
     * تحويل الحقول
     */
    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * العلاقة مع العيادة
     */
    public function dentalClinic()
    {
        return $this->belongsTo(DentalClinic::class);
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute()
    {
        return [
            'sufficient' => 'كافٍ',
            'damaged' => 'تالف',
            'low' => 'نقص',
        ][$this->status] ?? 'غير معروف';
    }

    /**
     * الحصول على لون الحالة
     */
    public function getStatusColorAttribute()
    {
        return [
            'sufficient' => '#5ECBC7', // أزرق فيروزي
            'damaged' => '#FF5252',    // أحمر
            'low' => '#FFD740',        // أصفر
        ][$this->status] ?? '#999999';
    }
}

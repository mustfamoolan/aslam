<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'opening_time',
        'closing_time',
    ];

    /**
     * تحويل الحقول إلى أنواع بيانات محددة
     *
     * @var array
     */
    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
    ];

    /**
     * العلاقة مع الأطباء
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}

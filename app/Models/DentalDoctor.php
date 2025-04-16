<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DentalDoctor extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     * يستخدم حقل 'phone' لتسجيل الدخول بدلاً من البريد الإلكتروني
     * يجب أن يكون رقم الهاتف فريداً لكل طبيب
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dental_specialty_id',
        'dental_clinic_id',
        'phone',
        'email',
        'password',
    ];

    /**
     * الحقول التي يجب إخفاؤها
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * العلاقة مع العيادة
     */
    public function clinic()
    {
        return $this->belongsTo(DentalClinic::class, 'dental_clinic_id');
    }

    /**
     * العلاقة مع التخصص
     */
    public function specialty()
    {
        return $this->belongsTo(DentalSpecialty::class, 'dental_specialty_id');
    }
}

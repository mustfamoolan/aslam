<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'specialty_id',
        'clinic_id',
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
        return $this->belongsTo(Clinic::class);
    }

    /**
     * العلاقة مع التخصص
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}

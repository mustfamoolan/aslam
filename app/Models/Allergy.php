<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها بشكل جماعي
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * العلاقة مع المرضى
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_allergy');
    }
}

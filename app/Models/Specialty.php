<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
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
     * العلاقة مع الأطباء
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}

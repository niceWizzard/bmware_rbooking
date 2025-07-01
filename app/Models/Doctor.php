<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $table = 'booking_doctors';
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    public function schedules() : HasMany {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }


}

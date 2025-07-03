<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentDetails extends Model
{
    protected  $table = "booking_appointment_details";

    protected $fillable = [
        'doctor_id',
        'appointment_id',
        'patient_id'
    ];

    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient(): BelongsTo {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointment(): BelongsTo {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

}

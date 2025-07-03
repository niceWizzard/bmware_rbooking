<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic',
        'appointment_number',
        'patient_id',
        'appointment_start',
        'appointment_date',
        'appointment_end',
        'appointment_for',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_start' => 'datetime:H:i:s',
        'appointment_end' => 'datetime:H:i:s',
    ];

    public function appointmentStartDateTime() : Attribute{
        return Attribute::get(
            fn () => $this->appointment_date
                ->copy()
                ->setTimeFrom($this->appointment_start)
        );
    }

    public function appointmentEndDateTime() : Attribute{
        return Attribute::get(
            fn () => $this->appointment_date
                ->copy()
                ->setTimeFrom($this->appointment_end)
        );
    }

    public function details() : HasOne {
        return $this->hasOne(AppointmentDetails::class, 'appointment_id', 'id');
    }

    public static function isPatientBookedAt(Patient $patient, Carbon $date) : bool {
        return self::where('patient_id', $patient->id)
            ->where('appointment_date', $date->toDateString())
            ->where('appointment_start', $date->toTimeString())
            ->exists();
    }

    public static function isDoctorBookedAt(Doctor $doctor, Carbon $date) : bool {
        return self::whereDate('appointment_date', $date)
            ->whereTime('appointment_start', $date)
            ->whereHas('details', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->exists();
    }

    public static function booted(): void
    {
        static::creating(static function (Appointment $appointment) {
            $appointment->appointment_number = self::generateAppointmentNumber();
        });
    }

    public static function generateAppointmentNumber(): string
    {
        $prefix = 'APT';
        $year = now()->year;
        $lastNumber = self::whereYear('created_at', now()->year)->count();
        $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        return $prefix . $year . $nextNumber;
    }



}

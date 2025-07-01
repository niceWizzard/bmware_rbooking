<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
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

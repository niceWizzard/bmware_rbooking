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


}

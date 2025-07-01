<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $table = 'doctor_schedules';
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory;

    protected $casts = [
        'day' => DayOfWeek::class,
    ];
}

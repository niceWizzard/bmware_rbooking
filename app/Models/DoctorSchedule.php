<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DoctorSchedule extends Model
{
    protected $table = 'doctor_schedules';
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start',
        'end',
        'day',
        'break_start',
        'break_end',
        'clinic',
    ];

    protected $casts = [
        'day' => DayOfWeek::class,
        'start' => 'datetime:H:i:s',
        'end' => 'datetime:H:i:s',
        'break_start' => 'datetime:H:i:s',
        'break_end' => 'datetime:H:i:s',
    ];

    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function availableSlotsAt(Carbon $date, ?User $user = null): array
    {
        if (!$this->start || !$this->end) {
            return [];
        }

        $availableTime = [];

        // Parse times as Carbon objects on the given date
        $start = $this->start->setDateFrom($date);
//        $breakStart = $this->break_time_start ? $this->break_time_start->setDateFrom($date) : null;
//        $breakEnd = $this->break_time_end ? $this->break_time_end->setDateFrom($date) : null;
        $end = $this->end->setDateFrom($date);

        // 1-hour slots before break
        $current = $start->copy();
        while ($current < $end) {
            if(
                Appointment::isPatientBookedAt($user->patient, $current) ||
                Appointment::isDoctorBookedAt($this->doctor, $current)
            ) {
                $current->addHour();
                continue;
            }
            $availableTime[] = $current->copy();
            $current->addHour();
        }

        return $availableTime;
    }


}

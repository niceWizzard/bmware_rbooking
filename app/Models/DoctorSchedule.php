<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    ];

    protected $appends = [
        'start_at',
        'end_at'
    ];

    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function startAt() : Attribute {
        return Attribute::get(function ($attributes) : Carbon {
            return Carbon::createFromFormat('H:i:s', $this->start)
                ->dayOfWeek($this->day->value);
        });
    }

    public function endAt() : Attribute {
        return Attribute::get(function ($attributes) : Carbon {
            return Carbon::createFromFormat('H:i:s', $this->end)
                ->dayOfWeek($this->day->value);
        });
    }

    public function breakStartAt() : Attribute {
        return Attribute::get(function ($attributes) : Carbon {
            return Carbon::createFromFormat('H:i:s', $this->break_start)
                ->dayOfWeek($this->day->value);
        });
    }

    public function breakEndAt() : Attribute {
        return Attribute::get(function ($attributes) : Carbon {
            return Carbon::createFromFormat('H:i:s', $this->break_end)
                ->dayOfWeek($this->day->value);
        });
    }

    public function availableSlotsAt(Carbon $date, ?User $user = null): array
    {
        if (!$this->start || !$this->end) {
            return [];
        }

        $availableTime = [];

        // Parse times as Carbon objects on the given date
        $start = $this->start_at->setDateFrom($date);
//        $breakStart = $this->break_time_start ? $this->break_time_start->setDateFrom($date) : null;
//        $breakEnd = $this->break_time_end ? $this->break_time_end->setDateFrom($date) : null;
        $end = $this->end_at->setDateFrom($date);

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

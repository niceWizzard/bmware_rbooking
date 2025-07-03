<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Doctor extends Model
{
    protected $table = 'booking_doctors';
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'code',
    ];

    public function schedules() : HasMany {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'id');
    }

    /**
     * @return array{Carbon[], DoctorSchedule|null}  Array of available slots and current schedule
     */
    public function availableSlotsAt(Carbon $date, User $user): array
    {
        $scheduleDay = DoctorSchedule::whereDoctorId($this->id, )
            ->where('day',$date->dayOfWeek)
            ->first();
        if (!$scheduleDay) {
            return [[], null];
        }
        return [$scheduleDay->availableSlotsAt($date, $user), $scheduleDay];
    }

    /**
     * Returns the days (int) where there are no schedule
     * @return array{int}
     */
    public function getDaysOff(): array {
        $schedules = $this->schedules->map(function(DoctorSchedule $schedule) {
            return $schedule->day->value;
        })->values()->toArray();

        return array_values(array_diff([0, 1, 2, 3, 4, 5, 6], $schedules));
    }

}

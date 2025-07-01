<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $casts = [
        'day' => DayOfWeek::class,
        'start' => 'datetime:H:i:s',
        'end' => 'datetime:H:i:s',
        'break_start' => 'datetime:H:i:s',
        'break_end' => 'datetime:H:i:s',
    ];

    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class);
    }


}

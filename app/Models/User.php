<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    public const ROLE_PATIENT = 'patient';
    public const ROLE_ADMIN = 'admin';

    protected $table = 'booking_users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'patient_id',
        'role',
        'email_verified_at',
        'patient_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function patient(): BelongsTo {
        return $this->belongsTo(Patient::class,  'patient_id');
    }

    public function admin() : BelongsTo {
        return $this->belongsTo(Admin::class,  'admin_id');
    }

    public function isPatient(): Attribute
    {
        return Attribute::get(fn () => $this->role === self::ROLE_PATIENT);
    }


    public function isAdmin(): Attribute
    {
        return Attribute::get(fn () => $this->role === self::ROLE_ADMIN);
    }

    public function getAppointmentsWith(string $doctorId): Collection|array
    {
        $doctor = Doctor::find($doctorId);
        if(is_null($doctor)) {
            return [];
        }

        return $this->patient->appointments()->whereHas('details', function($query) use($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->get();
    }

    public function getDashboardLink() : string {
        return match ($this->role) {
            default => route('patient.book'),
            self::ROLE_ADMIN => route('admin.dashboard'),
        };
    }

    public function isPatientVerified() : bool {
        return !is_null($this->patient_verified_at);
    }

    public function verifyPatientRecord() : void {
        $this->update([
            'patient_verified_at' => now(),
        ]);
    }

    public function canStillBookToday() : bool {
        return $this->patient->appointmentsCreatedToday()->count() < 3;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'gender',
        'civil_status',
        'height',
        'weight',
        'mobile',
        'telephone',
        'type_of_transaction',
        'card_number',
        'address',
        'notes',
        'occupation',
        'company_name',
        'guardian_name',
        'relationship',
        'guardian_address',
        'referring_physician',
        'primary_care_physician',
        'referring_institution',
        'pastmedical',
        'medical',
        'vaccination',
        'drug',
        'dosage',
        'obgyn',
        'others',
    ];

    public function user() : HasOne {
        return $this->hasOne(User::class, 'patient_id', 'id');
    }

    public function appointments() : HasMany {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function appointmentsCreatedToday()
    {
        return $this->appointments()->whereDate('created_at', Carbon::today())->get();
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


class Patient extends Model
{

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }
}

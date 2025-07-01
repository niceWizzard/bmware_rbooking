<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Admin extends Model
{
    protected $table = 'booking_admins';
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;


    public function user() : HasOne {
        return  $this->hasOne(User::class);
    }
}

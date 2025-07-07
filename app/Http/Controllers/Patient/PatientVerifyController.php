<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientVerifyController extends Controller
{
    public function verifyPatient() {
        return Inertia::render('Patient/Verify');
    }
}

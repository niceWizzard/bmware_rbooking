<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PatientVerifyController extends Controller
{
    public function verifyPatient() {
        $user = Auth::user();
        if($user->isPatientVerified()) {
            return redirect()->intended(route('patient.book'));
        }
        $patient = $user->patient;
        return Inertia::render('Patient/Verify', [
            'mobile' => $patient->mobile,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
        ]);
    }

    public function verify(Request $request) {
        $user = Auth::user();
        if($user->isPatientVerified()) {
            return redirect()->intended(route('patient.book'));
        }

        $data = $request->validate([
            'mobile' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
        ]);

        $existingRecord  = Patient::whereMobile($request->mobile)->first();
        if(!is_null($existingRecord) && ($existingRecord->mobile !== $request->mobile
            || $existingRecord->first_name !== $request->first_name
            || $existingRecord->last_name !== $request->last_name))
        {
            throw ValidationException::withMessages([
                'mobile' => ['Invalid record details.'],
            ]);
        }
        $user?->verifyPatientRecord();
        return redirect()->intended(route('patient.book'));
    }


}

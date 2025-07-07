<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientInfoController extends Controller
{
    public function update(Request $request) {
        $user = Auth::user();
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:Male,Female'], // Adjust 'in' options based on your domain
            'civil_status' => ['required', 'string', 'in:Single,Married,Divorced,Widowed'], // Adjust as needed
            'height' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'mobile' => [
                'required', 'string', 'max:255',
                Rule::unique('patients', 'mobile')
                    ->ignore($user?->patient_id, 'id')
            ],
            'telephone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:255'],
            'guardian_address' => ['nullable', 'string', 'max:255'],
        ]);

        if($data['birthdate']) {
            $data['birthdate'] = Carbon::parse($data['birthdate'])->toDateString();
        }

        $user->patient->update($data);

        return back()->with('message', 'Profile updated successfully.');

    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed'],
            'mobile' => ['required'],
        ]);

        $mobile = $request->mobile;
        $patient = Patient::where('mobile', $mobile)->first();
        $patientVerified = false;
        if(!is_null($patient)){
            if(!is_null($patient->user)) {
                return back()->withErrors([
                    'mobile' => 'Number already taken.'
                ]);
            }
        } else {
            $patientVerified = true;
            $patient = Patient::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->mobile,
                'birthdate' => Carbon::today()->toDateTimeString(),
                'gender' => 'Male',
                'civil_status' => 'Single',
                'type_of_transaction' => 'Cash',
            ]);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'patient_id' => $patient->id,
            'patient_verified_at' => $patientVerified ? now() : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('patient.book'));
    }
}

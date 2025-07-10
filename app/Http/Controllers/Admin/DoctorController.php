<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DoctorController extends Controller
{
    public function index() {
        $doctors = Doctor::withExists('schedules')->get();
        return Inertia::render('Admin/Doctor/DoctorList', [
            'doctors' => $doctors,
        ]);
    }
    public function create() {
        return Inertia::render('Admin/Doctor/DoctorCreate');
    }
    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'unique:booking_doctors,code'],
            'specialty' => ['required', 'string'],
            'profile_picture' => ['required', 'file', 'max:2048']
        ]);
        $path = $request->file('profile_picture')?->store('profile_pictures', 'public');
        $doctor = Doctor::create([
            ...$data,
            'profile_picture' => $path,
        ]);
        return redirect(route('schedule.create', $doctor->id));
    }

    public function delete(string $id) {
        $doctor = Doctor::findOrFail($id);
        if(!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        $doctor->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    public function edit(string $id) {
        return Inertia::render('Admin/Doctor/DoctorEdit', [
            'doctor' => Doctor::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $id) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', Rule::unique('booking_doctors', 'code')->ignore($id)],
            'specialty' => ['required', 'string'],
            'profile_picture' => ['required', 'file', 'max:2048']
        ]);

        $doctor = Doctor::findOrFail($id);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if it exists
            if ($doctor->profile_picture && Storage::disk('public')->exists($doctor->profile_picture)) {
                Storage::disk('public')->delete($doctor->profile_picture);
            }

            // Store new picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        // Update doctor
        $doctor->update($data);

        return Redirect::route('doctor.list');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientManagementController extends Controller
{
    public function index() {
        $usersWithPatient = User::whereRole(User::ROLE_PATIENT)
            ->with(['patient' => function ($query) {
                $query->withCount('appointments');
            }])
            ->get();
        return Inertia::render('Admin/Doctor/PatientList', [
            'patients' => $usersWithPatient
        ]);
    }

    public function delete(string $id) {
        if(!Auth::user()->is_admin) {
            return response()->json([
               'success' => false,
               'message' => 'You cannot delete this user!',
            ]);
        }
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
        $user->delete();
        return response()->json([
            'success' => true,
        ]);
    }

}

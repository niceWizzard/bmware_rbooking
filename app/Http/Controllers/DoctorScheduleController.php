<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorScheduleController extends Controller
{
    public function index(Request $request) {
        \Auth::user()->load('admin');
        return Inertia::render('Admin/CreateSchedule',  [

        ]);
    }
}

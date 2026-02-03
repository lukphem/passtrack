<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        return view('lecturer.dashboard'); // make sure this view exists
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class SchoolMapController extends Controller
{
    public function index()
    {
        // Select offices that have valid coordinates
        $offices = Office::with('department')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('map.index', compact('offices'));
    }
}

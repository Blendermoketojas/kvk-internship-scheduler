<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\internship;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function sendEvent(Request $request){

        $validatedData = $request->validate([
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $entry = internship::create($validatedData);

        return response()->json($entry, 201);

    }
    public function pullEvents(Request $request){
        $userId = $request->input('user_id');

        $validatedData = $request->validate([
            'user_id' => 'required|integer',
        ]);

        $internships = Internship::where('user_id', $userId)->get();

        return response()->json($internships);

    }

}

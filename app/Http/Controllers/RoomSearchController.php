<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomSearchController extends Controller
{
    public function index(Request $request)
    {
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $guests = $request->input('guests');

   return view('rooms.search-results', compact('checkIn', 'checkOut', 'guests'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RFIDController extends Controller
{

    public function index()
    {
        return view('rfid.index');
    }



    public function assign()
    {
        return view('rfid.assign');
    }


}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    public function index()
    {
        return view('adjustment.index');
    }
}

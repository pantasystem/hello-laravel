<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    //
    public function new()
    {
        return view('new_note');
    }
}

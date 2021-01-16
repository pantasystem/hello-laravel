<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateNoteRequest;
use App\Models\Note;

class NoteController extends Controller
{
    //
    public function new()
    {
        return view('new_note');
    }

    public function store(CreateNoteRequest $request)
    {
        // title, textを連想配列で取得します。
        $params = $request->only('title', 'text');

        $crated_note = Note::create($params);
    }
}

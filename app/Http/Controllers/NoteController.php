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
        return redirect()->route('notes');
    }

    public function index()
    {
        $notes = Note::all();
        return view('notes', ['notes' => $notes]);
    }

    public function show($noteId)
    {
        // noteIdをもとにNoteを取得します。
        // select * from notes where id = $noteId limit 1;
        // $noteIdのノートが存在しなかった場合404 Not foundが表示されます
        $note = Note::findOrFail($noteId);
        return view('notes_detail', ['note' => $note]);
    }
}

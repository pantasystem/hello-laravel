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

    public function edit($noteId)
    {
        $note = Note::findOrFail($noteId);

        // 編集データの初期値としてnoteが必要なので渡しています。
        return view('edit_note', ['note' => $note]);
    }

    public function update(CreateNoteRequest $request, $noteId)
    {
        // 更新対称のNoteを取得します。
        $note = Note::findOrFail($noteId);

        // title, textを連想配列で取得します。
        $params = $request->only('title', 'text');

        // 取得したNoteインスタンスに送られてきたデータ(title, text)を適応します。
        $note->fill($params);

        // Noteインスタンスの状態を保存します。
        $note->save();

        // 詳細画面に遷移する
        return redirect()->route('get', ['noteId' => $noteId]);
    }

    public function delete($noteId)
    {
        $note = Note::findOrFail($noteId);

        // 削除する
        $note->delete();

        // 一覧画面へ遷移する
        return redirect()->route('notes');
    }
}

@extends('layouts.app')

@section('title')
{{ $note->title }}
@endsection

@section('content')
<div>
    <h2>{{ $note->title }}</h2>
    <p>
        {{ $note->text }}
    </p>
    <a href="{{ route('notes')}}">一覧へ</a>
    <a href="{{ route('notes.edit', ['noteId' => $note->id])}}">編集</a>
    <form method="POST" action="{{ route('notes.delete', ['noteId' => $note->id])}}">
        @csrf
        @method('DELETE')
        <button type="submit">完全に削除する</button>
    </form>
</div>
@endsection
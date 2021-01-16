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
</div>
@endsection
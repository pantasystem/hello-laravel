@extends('layouts.app')

@section('title')
メモ一覧
@endsection

@section('content')
<div>
    <h2>メモ一覧</h2>
    <ul>
        @foreach($notes as $note)
            <li>
                <!-- タイトルを表示する -->
                {{ $note->title }}
            </li>
        @endforeach
    </ul>
    <a href="{{ route('notes.new')}}">新規作成</a>
</div>
@endsection
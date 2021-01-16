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
                <!--詳細画面のリンクを設定する-->
                <a href="{{ route('get', ['noteId' => $note->id ])}}">
                    <!-- タイトルを表示する -->
                    {{ $note->title }}
                </a>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('notes.new')}}">新規作成</a>
</div>
@endsection
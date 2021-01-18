@extends('layouts.app')

@section('title')
メモを編集
@endsection
@section('content')
<form method="POST" action="{{ route('notes.update', ['noteId' => $note->id]) }}">
    @csrf
    @method('PUT')

    <div>
        タイトル:<input type="text" name="title" value="{{ old('title', $note->title) }}">
        @error('title') 
            <p> {{ $message }}</p> 
        @enderror
    </div>
    <div>
        <p>本文:</p>
        <textarea type="text" name='text'>
            {{ old('text', $note->text)}}    
        </textarea>
        @error('text')
            <p> 
                {{ $message }}
            </p>
        @enderror
    
    </div>
    <div>
        <button type="submit">更新</button>
    </div>
</form>
@endsection    
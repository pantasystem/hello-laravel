@extends('layouts.app')

@section('title')
メモを作成
@endsection
@section('content')
<form method="POST" action="{{ route('notes.create') }}">
    @csrf

    <div>
        タイトル:<input type="text" name="title" value="{{ old('title') }}">
        @error('title') 
            <p> {{ $message }}</p> 
        @enderror
    </div>
    <div>
        <p>本文:</p>
        <textarea type="text" name='text'>
            {{ old('text')}}    
        </textarea>
        @error('text')
            <p> 
                {{ $message }}
            </p>
        @enderror
    
    </div>
    <div>
        <button type="submit">作成</button>
    </div>
</form>
@endsection    
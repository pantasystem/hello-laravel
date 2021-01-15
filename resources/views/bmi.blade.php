@extends('layouts.app')

@section('title')
BMIを測定
@endsection
@section('content')
<form method="POST" action="{{ route('bmi.store') }}">
    @csrf
    <div>
        身長:<input type="text" name="height" value="{{ old('height') }}">cm
    </div>
    @error('height')
        <div> {{ $message }}</div>
    @enderror

    <div>
        体重:<input type="text" name="weight" value="{{ old('weight') }}">kg
    </div>

    @error('weight')
        <div>{{ $message }}</div>
    @enderror

    @if(session('bmi'))
    <div>
        BMIは{{ session('bmi') }}です。
    </div>
    @endif
    <button type="submit">送信</button>
        
</form>
@endsection

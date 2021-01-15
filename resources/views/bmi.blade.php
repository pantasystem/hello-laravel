@extends('layouts.app')

@section('title')
BMIを測定
@endsection
@section('content')
<form method="POST" action="{{ route('bmi.store') }}">
    @csrf
    <div>
        身長:<input type="text" name="height">
    </div>
    <div>
        体重:<input type="text" name="weight">
    </div>
    <button type="submit">送信</button>
        
</form>
@endsection

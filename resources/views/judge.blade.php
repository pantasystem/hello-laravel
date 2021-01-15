@extends('layouts.app')
@section('title')
    基数偶数判定
@endsection

@section('content')
    <h1>基数偶数判定だ！！</h1>
    @if($number % 2 == 0)
        <p>偶数だ！！</p>
    @else 
    <p>奇数だ！！</p>
    @endif
@endsection

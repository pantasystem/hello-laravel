@extends('layouts.app')

@section('title')
Hello Blade
@endsection
@section('content')
<h1>bladeに入門！！</h1>
<p>{{ $message }}</p>

<!--エスケープされない-->
<p>{!! $message !!}</p>
@endsection

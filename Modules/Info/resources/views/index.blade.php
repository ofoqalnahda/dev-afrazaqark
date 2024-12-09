@extends('info::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('info.name') !!}</p>
@endsection

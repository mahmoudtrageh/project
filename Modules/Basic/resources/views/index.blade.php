@extends('basic::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('basic.name') !!}</p>
@endsection

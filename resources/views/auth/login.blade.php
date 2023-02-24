@extends('layouts.app')

@section('content')
    <login-component token-csrf="{{ @csrf_token() }}"></login-component>
@endsection

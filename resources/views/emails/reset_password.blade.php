@extends('layouts.email')

@section('custom_css')
    
@endsection

@section('content')
    <h3>Dear {{$data->first_name.' '.$data->last_name}},</h3>
    <p>Please click bellow link to reset your password.</p>
    <p><a href="{{route('reset-action', ['token' => $data->verification_token])}}">ACTIVATE</a></p>
    <p>Or copy the url provided bellow and paste to your browser address bar.</p>
    <p>{{route('reset-action', ['token' => $data->verification_token])}}</p>
@endsection

@section('custom_js')
    
@endsection
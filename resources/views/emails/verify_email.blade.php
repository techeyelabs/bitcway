@extends('layouts.email')

@section('custom_css')
    
@endsection

@section('content')
    <h3>Dear {{$data->first_name.' '.$data->last_name}},</h3>
    <p>Welcome to BitcWay. Your registration is completed.Please click the link bellow to activate your account.</p>
    <p><a href="{{route('verify-email', ['token' => $data->verification_token, app()->getLocale()])}}">ACTIVATE</a></p>
    <p>Or copy the url provided bellow and paste to your browser address bar.</p>
    <p>{{route('verify-email', ['token' => $data->verification_token,  app()->getLocale()])}}</p>
@endsection

@section('custom_js')
    
@endsection
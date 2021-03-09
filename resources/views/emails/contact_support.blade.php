@extends('layouts.email')

@section('custom_css')
    
@endsection

@section('content')
    <p>
        Support Contact From: <br>
        Name: {{$data->User->first_name.' '.$data->User->last_name}}
        <br>
        Email: {{$data->User->email}}
        <br>
        Company: {{$data->User->company->company_name}}
    </p>

    <p>
        -------------------------------------------------------------
        <br>
        {{$data->message}}
        <br>
        -------------------------------------------------------------
    </p>
@endsection

@section('custom_js')
    
@endsection
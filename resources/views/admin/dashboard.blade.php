@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
    <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
@endsection

@section('js')
    <script>
        console.log('Hi!');
    </script>
@endsection

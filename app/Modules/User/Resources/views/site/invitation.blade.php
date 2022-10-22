@extends('mail.index')

@section('header')
    Invitation to collaborate
@endsection

@section('content')
    You have been invited to start your new project.
@endsection

@section('button')
    <a class="button" href="{{ $site }}">
        Get my password
    </a>
@endsection

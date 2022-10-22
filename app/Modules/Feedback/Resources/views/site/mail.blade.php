@extends('mail.index')

@section('header')
    Feedback from the site
@endsection

@section('content')
    User has sent you a feedback from the site.
@endsection

@section('table')
    <table class="table horizontal">
        <thead>
        <tr>
            <td width="30%">Item</td>
            <td width="70%">Description</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Name:</td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><a href='mailto:{{ $email }}'>{{ $email }}</a></td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td><a href='tel:{{ $phone }}'>{{ $phone }}</a></td>
        </tr>
        @if($msg !== '')
            <tr>
                <td>Message:</td>
                <td>{!! nl2br($msg) !!}</td>
            </tr>
        @endif
        </tbody>
    </table>
@endsection

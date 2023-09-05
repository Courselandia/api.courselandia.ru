@extends('mail.index')

@section('header')
    {{ trans('views.recovery.header') }}
@endsection

@section('content')
    {{ trans('views.recovery.content') }}
@endsection

@section('button')
    <a class="button" href="{{ $site }}/forget/reset/{{ $user->id }}?code={!! urlencode($code) !!}">
        {{ trans('access::views.recovery.button') }}
    </a>
@endsection

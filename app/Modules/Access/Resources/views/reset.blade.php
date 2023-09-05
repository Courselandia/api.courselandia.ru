@extends('mail.index')

@section('header')
    {{ trans('access::views.reset.header',
        [
        'name' => $user->first_name . ' ' . $user->second_name
        ]
    ) }}
@endsection

@section('content')
    {{ trans('access::views.reset.content',
        [
        'name' => $user->first_name . ' ' . $user->second_name,
        'url' => $site . '/contact-us'
        ]
    ) }}
@endsection

@section('button')
    <a class="button" href="{{ $site }}">
        {{ trans('access::views.reset.button') }}
    </a>
@endsection

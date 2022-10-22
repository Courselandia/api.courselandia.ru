<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" />

    @if(isset($site))
        <base href="{{ $site }}"/>
    @else
        <base href="{{ Config::get('app.url') }}"/>
    @endif

    <style>
        BODY
        {
            padding: 0;
            margin: 0;
            background-color: #f1f7fa;
        }

        BODY, TD
        {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }

        TABLE
        {
            border-collapse: collapse;
            padding: 0;
            border-spacing: 0;
            border: 0;
            width: 100%;
        }

        TABLE.widthAuto
        {
            width: auto;
        }

        TD
        {
            vertical-align: top;
            border-spacing: 0;
            padding: 0;
            border-width: 0;
        }

        P
        {
            padding: 0;
            margin: 0;
            padding-bottom: 20px;
        }

        TABLE P:last-child
        {
            padding-bottom: 0;
        }

        IMG
        {
            border: 0;
        }

        UL,
        OL,
        LI
        {
            margin: 0;
            padding: 0;
        }

        UL
        {
            margin-left: 44px;
            margin-bottom: 18px;
        }

        OL
        {
            margin-left: 45px;
            margin-bottom: 18px;
        }

        UL LI,
        OL LI
        {
            margin-bottom: 2px;
            padding-left: 5px;
        }

        UL,
        UL LI
        {
            list-style: square;
        }

        a:link,
        a:visited,
        a:active,
        a:hover
        {
            text-decoration: underline;
            color: #2490e8;
        }

        a:hover
        {
            text-decoration: none;
            color: #1a96fb;
        }

        .page
        {
            text-align: center;
            padding: 35px;
        }
    </style>
</head>

<body>

<div class="page">
    <div class="logo">
        <a href="{{ config('app.url') }}"></a>
    </div>

    <div class="header">
        @yield('header')
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="table">
        @yield('table')
    </div>

    <div class="button">
        @yield('button')
    </div>

    @if(Config::get('soc.twitter') || Config::get('soc.instagram') || Config::get('soc.facebook'))
        <div class="social">
            @if(Config::get('soc.twitter'))
                <div class="icon tw">
                    <a href="{{ Config::get('soc.twitter') }}" target="_blank"></a>
                </div>
            @endif

            @if(Config::get('soc.instagram'))
                <div class="icon inst">
                    <a href="{{ Config::get('soc.instagram') }}" target="_blank"></a>
                </div>
            @endif

            @if(Config::get('soc.facebook'))
                <div class="icon f">
                    <a href="{{ Config::get('soc.facebook') }}" target="_blank"></a>
                </div>
            @endif
        </div>
    @endif
</div>

</body>

</html>

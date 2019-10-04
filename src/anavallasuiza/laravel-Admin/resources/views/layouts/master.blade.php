<?php use Admin\Library\Html; ?>

<!DOCTYPE html>

<html lang="{{App::getLocale()}}">
    <head>
        <meta charset="utf-8">

        <title>{{ Meta::get('title') }}</title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet" type="text/css" />
        <link href="//fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css" />
        <link href="{{ Html::elixir('css/app.min.css') }}" rel="stylesheet" type="text/css" />

        @yield('head')
    </head>

    <body id="top" class="skin-blue">
        @yield('body')

        <script src="{{ route('admin.gettext.js') }}" type="text/javascript"></script>
        <script src="{{ Html::elixir('js/app.min.js', 'admin') }}" type="text/javascript"></script>
    </body>
</html>

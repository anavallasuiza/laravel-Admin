<?php use Admin\Library\Html;

?>

<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10 no-js" lang="{{App::getLocale()}}"> <![endif]-->
<html lang="{{App::getLocale()}}">
    <head>
        @section('head')
        <meta charset="utf-8">

        <title>{{ Meta::meta('title') }}</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet" type="text/css" />
        <link href="//fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css" />
        <link href="{{ Html::elixir('css/app.min.css') }}" rel="stylesheet" type="text/css" />

        <!--[if lt IE 9]>
          <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.min.js"></script>
          <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @show
    </head>

    <body id="top" class="skin-blue">
        @yield('body')

        <script src="{{ url('admin/gettext.js') }}" type="text/javascript"></script>
        <script src="{{ Html::elixir('js/app.min.js', 'admin') }}" type="text/javascript"></script>
    </body>
</html>

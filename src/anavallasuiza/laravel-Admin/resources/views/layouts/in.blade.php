@extends('admin::layouts.master')

@section('body')
<header class="header">
    <a href="{{ url('admin') }}" class="logo">
        {{ __('Home')}}
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ __('Toggle navigation') }}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                        {{ __('language-'.$LOCALE) }}
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        @foreach ($LOCALES as $locale)
                        <li role="presentation"><a role="menuitem" href="{{ query('locale', $locale) }}" tabindex="-1">{{ __('language-'.$locale) }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="user user-menu">
                    <a href="{{ url('/') }}" target="_blank">
                        <i class="glyphicon glyphicon-eye-open"></i>
                        {{ __('Go to web') }}
                    </a>
                </li>

                <li class="user user-menu">
                    <a href="{{ route('admin::logout') }}">
                        <i class="glyphicon glyphicon-log-out"></i>
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper row-offcanvas row-offcanvas-left">
    @include('admin::layouts.left-side')
    @yield('right-side')
</div>
@stop
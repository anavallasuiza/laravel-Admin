<?php use Admin\Library\Html; ?>

@extends('admin::layouts.master')

@section('body')
<div class="wrapper">
    <header class="main-header">
        <a href="{{ url('admin') }}" class="logo">
            {{ __('Home')}}
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">{{ __('Toggle navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true">
                            {{ __('language-'.$LOCALE) }}
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @foreach ($LOCALES as $locale)
                            <li role="presentation"><a role="menuitem" href="{{ Html::query('locale_admin', $locale) }}" tabindex="-1">{{ __('language-'.$locale) }}</a></li>
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
                        <a href="{{ route('admin.logout') }}">
                            <i class="glyphicon glyphicon-log-out"></i>
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        @include('admin::layouts.left-side')
    </aside>

    <div class="content-wrapper">
        @yield('right-side')
    </div>
</div>
@stop

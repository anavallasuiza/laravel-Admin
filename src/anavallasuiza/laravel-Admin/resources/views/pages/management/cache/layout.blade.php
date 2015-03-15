@extends('admin::layouts.right-side')

@section('content')

<?php $segment = Request::segment(4); ?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li {!! ($segment === 'views') ? 'class="active"' : '' !!}><a href="{{ route('admin.management.cache.views') }}">{{ __('Views') }}</a></li>
        <li {!! ($segment === 'apc') ? 'class="active"' : '' !!}><a href="{{ route('admin.management.cache.apc') }}">{{ __('APC') }}</a></li>
        <li {!! ($segment === 'memcache') ? 'class="active"' : '' !!}><a href="{{ route('admin.management.cache.memcache') }}">{{ __('Memcache') }}</a></li>
        <li {!! ($segment === 'memcached') ? 'class="active"' : '' !!}><a href="{{ route('admin.management.cache.memcached') }}">{{ __('Memcached') }}</a></li>
        <li {!! ($segment === 'files') ? 'class="active"' : '' !!}><a href="{{ route('admin.management.cache.files') }}">{{ __('Files') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane in active">
            @yield('subcontent')
        </div>
    </div>
</div>

@stop

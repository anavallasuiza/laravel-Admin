@extends('admin::layouts.right-side')

@section('content')

<div class="error-page">
    <h2 class="headline text-info">404</h2>

    <div class="error-content">
        <h3>
            <i class="fa fa-warning text-yellow"></i>
            {{ __('Oops! Page not found.') }}
        </h3>

        <p>{{ __('We could not find the page you were looking for.') }}</p>
        <p>{{ __('Maybe you are loading an old location stored in bookmarks?') }}</p>
        <p>{{ __('Meanwhile, you may <a href="%s">return to dashboard</a>.', url('admin')) }}</p>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            {{ __('Current location') }}
        </div>

        <div class="panel-body">
            {{ Request::url() }}
        </div>
    </div>
</div>

@stop
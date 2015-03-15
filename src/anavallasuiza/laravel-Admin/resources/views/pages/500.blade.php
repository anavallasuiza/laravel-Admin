@extends('admin::layouts.right-side')

@section('content')

<section class="error-page">
    <h2 class="headline text-info">{{ $code }}</h2>

    <div class="error-content">
        <h3>
            <i class="fa fa-bolt text-red"></i>
            {{ __('Oops! Terrible error!') }}
        </h3>

        <p>{{ __('Looks like something went wrong!') }}</p>
        <p>{{ __('We track these errors automatically, but if the problem persists feel free to contact us.') }}</p>
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

    <div class="panel panel-warning">
        <div class="panel-heading">
            {{ __('Error message') }}
        </div>

        <div class="panel-body">
            {{ $message }}
        </div>
    </div>
</section>

@stop

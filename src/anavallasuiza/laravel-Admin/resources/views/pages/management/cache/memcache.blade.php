@extends('admin::pages.management.cache.layout')

@section('subcontent')

<h4>{{ __('Used Memcache Cache') }}</h4>

<div class="progress">
    <div class="progress-bar progress-bar-{{ ($percent < 60) ? 'success' : (($pecent < 90) ? 'warning' : 'danger') }}" role="progressbar" aria-valuenow="{{ $used }}" aria-valuemin="0" aria-valuemax="{{ $total }}" style="width: {{ $percent }}%;">
        <strong>{{ __('%s of %sMb', $used, $total) }}</strong>
    </div>
</div>

<div class="progress">
    <div class="progress-bar progress-bar-{{ ($percent < 60) ? 'success' : (($pecent < 90) ? 'warning' : 'danger') }}" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percent }}%;">
        <strong>{{ $percent }}%</strong>
    </div>
</div>

<form method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_processor" value="memcache" />

    <div class="box-footer clearfix">
        <div class="pull-right">
            <button type="submit" class="btn btn-success">
                {{ __('Clear Memcache Cache') }}
            </button>
        </div>
    </div>
</form>

@stop

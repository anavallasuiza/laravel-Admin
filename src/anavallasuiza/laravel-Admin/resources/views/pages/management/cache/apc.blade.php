@extends('admin::pages.management.cache.layout')

@section('subcontent')

<h4>{{ __('Used APC Cache') }}</h4>

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
    {!! Form::token() !!}

    <input type="hidden" name="action" value="cacheApc" />

    <div class="box-footer clearfix">
        <div class="pull-right">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="_action" value="cacheApc" class="btn btn-success">
                {{ __('Clear APC Cache') }}
            </button>
        </div>
    </div>
</form>

@stop
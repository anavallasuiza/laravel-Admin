@extends('admin::layouts.right-side')

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-first" data-toggle="tab">{{ __('First Database') }}</a></li>
        <li><a href="#tab-second" data-toggle="tab">{{ __('Second Database') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab-first">
            <pre>{{ $db1 }}</pre>
        </div>

        <div class="tab-pane" id="tab-second">
            <pre>{{ $db2 }}</pre>
        </div>
    </div>
</div>

@stop

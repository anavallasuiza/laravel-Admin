@extends('admin::layouts.right-side')

@section('content')

<form method="post" enctype="multipart/form-data">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-data" data-toggle="tab">{{ __('Data') }}</a></li>
            @if ($row->id)
            <li><a href="#tab-logs" data-toggle="tab">{{ __('Logs') }}</a></li>
            <li><a href="#tab-sessions" data-toggle="tab">{{ __('Sessions') }}</a></li>
            @endif
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab-data">
                {!! $form->tokenAndFake() !!}
                {!! $form->html() !!}
            </div>

            @if ($row->id)
            <div class="tab-pane" id="tab-logs">
                @include('admin::pages.management.users.logs')
            </div>

            <div class="tab-pane" id="tab-sessions">
                @include('admin::pages.management.users.sessions')
            </div>
            @endif
        </div>
    </div>

    @include ('admin::molecules.edit-footer')
</form>

@stop

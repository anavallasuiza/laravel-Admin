@extends('admin::layouts.right-side')

@section('content')

<form method="post" enctype="multipart/form-data">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-data" data-toggle="tab">{{ __('Data') }}</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab-data">
                {!! $form->tokenAndFake() !!}
                {!! $form->html() !!}
            </div>
        </div>
    </div>

    <div class="box-footer clearfix">
        <button type="submit" class="btn btn-success pull-right">
            {{ $row->id ? __('Update') : __('Create') }}
        </button>
    </div>
</form>

@stop

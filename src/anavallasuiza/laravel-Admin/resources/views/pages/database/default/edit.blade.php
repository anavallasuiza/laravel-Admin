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

    @include ('admin::molecules.edit-footer')
</form>

@stop

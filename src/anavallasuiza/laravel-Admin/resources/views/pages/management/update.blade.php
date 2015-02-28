@extends('admin::layouts.right-side')

@section('content')

<form class="text-center well submit-wait" data-message="{{ __('Please wait...') }}" method="post">
    {!! Form::token() !!}

    <input type="hidden" name="_action" value="update" />

    <div class="row">
        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="repository" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update repository') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="composer" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update composer') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="npm" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update npm') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="bower" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update bower') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="grunt" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update grunt') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" {{ empty($I->admin) ? 'disabled' : '' }} name="update" value="gulp" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Update gulp') }}
            </button>
        </div>
    </div>
</form>

@if ($action)
<pre><code>{{ $response }}</code></pre>
@endif

@stop
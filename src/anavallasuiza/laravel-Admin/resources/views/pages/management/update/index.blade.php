@extends('admin::layouts.right-side')

@section('content')

<form class="text-center well submit-wait" data-message="{{ __('Please wait...') }}" method="post">
    {!! token() !!}

    <div class="row">
        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="git" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Git') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="composer" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Composer') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="npm" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('npm') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="bower" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('bower') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="grunt" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('grunt') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_action" value="gulp" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('gulp') }}
            </button>
        </div>
    </div>
</form>

@if ($action)
<pre><code>{{ $response }}</code></pre>
@endif

@stop
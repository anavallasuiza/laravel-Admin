@extends('admin::layouts.right-side')

@section('content')

<form class="text-center well submit-wait" data-message="{{ __('Please wait...') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <div class="row">
        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="git" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Git') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="composer" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('Composer') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="npm" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('npm') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="bower" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('bower') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="grunt" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('grunt') }}
            </button>
        </div>

        <div class="col-sm-2 form-group">
            <button type="submit" name="_processor" value="gulp" class="btn btn-success btn-block">
                <i class="glyphicon glyphicon-refresh"></i>
                {{ __('gulp') }}
            </button>
        </div>
    </div>
</form>

@if ($processor)
<pre><code>{{ $response }}</code></pre>
@endif

@stop

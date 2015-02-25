@extends('admin::pages.management.cache.layout')

@section('subcontent')

<form method="get">
    <div class="form-group">
        <select name="view" class="form-control" data-change-submit>
            <option value="">{{ __('Choose a view') }}</option>
            @foreach ($files as $row)
            <option value="{{ $row }}" {{ ($row === $view) ? 'selected' : '' }}>{{ $row }}</option>
            @endforeach
        </select>
    </div>
</form>

@if ($contents)

<pre class="line-numbers"><code class="language-php">{{{ $contents }}}</code></pre>

@endif

@stop
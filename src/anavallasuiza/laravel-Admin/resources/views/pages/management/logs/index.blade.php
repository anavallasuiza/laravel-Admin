@extends('admin::layouts.right-side')

@section('content')

<form id="form-logs" class="well" method="get">
    <div class="row">
        <div class="col-sm-4 form-group">
            <select name="log" class="form-control" data-change-submit>
                <option value="">{{ __('Choose a log') }}</option>

                @foreach ($files as $row)
                <option value="{{ $row }}" {{ ($row === $log) ? 'selected' : '' }}>{{ $row }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-4 form-group">
            <select name="date" class="form-control" data-change-submit>
                <option value="day" {{ ($date === 'day') ? 'selected' : '' }}>{{ __('Last day') }}</option>
                <option value="week" {{ ($date === 'week') ? 'selected' : '' }}>{{ __('Last week') }}</option>
                <option value="month" {{ ($date === 'month') ? 'selected' : '' }}>{{ __('Last month') }}</option>
                <option value="" {{ ($date === '') ? 'selected' : '' }}>{{ __('Always') }}</option>
            </select>
        </div>

        <div class="col-sm-4 form-group">
            <select name="raw" class="form-control" data-change-submit>
                <option value="" {{ empty($raw) ? 'selected' : '' }}>{{ __('Formatted') }}</option>
                <option value="raw" {{ $raw ? 'selected' : '' }}>{{ __('RAW') }}</option>
            </select>
        </div>
    </div>
</form>

@if ($contents && is_string($contents))

    <pre><code>{{ $contents }}</code></pre>

@elseif ($contents && is_array($contents))

    @foreach ($contents as $row)
    <div class="alert alert-{{ $row['class'] }}">
        <p>
            <span class="badge">{{ $row['date'] }}</span>
            <strong>{{ $row['status'].' '.($row['file'] ? ($row['file'].' ['.$row['line'].']') : '') }}</strong>
        </p>

        <p>{{{ $row['short'] ?: $row['message'] }}}</p>
    </div>
    @endforeach

@elseif ($log)

    <div class="alert alert-info">
        {{ __('There aren\'t logs with choosen options') }}
    </div>

@endif

@stop

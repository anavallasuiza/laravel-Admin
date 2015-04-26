@extends('admin::pages.management.cache.layout')

@section('subcontent')

<h4>{{ __('Used Files Cache') }}</h4>

<form method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_processor" value="files" />

    <table class="table">
        @foreach ($folders as $folder)
        <tbody>
            <tr>
                <th style="padding-right: 20px;">{{ $folder['name'] }}</th>

                <td style="width: 100%">
                    <div class="progress">
                        <div class="progress-bar progress-bar-{{ ($folder['percent'] < 60) ? 'success' : (($folder['percent'] < 90) ? 'warning' : 'danger') }}" role="progressbar" aria-valuenow="{{ $folder['percent'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $folder['percent'] }}%;">
                            <strong>{{ __('%s of %sMb', $folder['size'], $total) }}</strong>
                        </div>
                    </div>
                </td>

                <td class="text-center" style="padding: 8px 40px;">
                    <input type="checkbox" name="delete[]" value="{{ $folder['name'] }}" />
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>

    <div class="box-footer clearfix">
        <div class="pull-right">
            <button type="submit" class="btn btn-success">
                {{ __('Delete Selected Cache Folders') }}
            </button>
        </div>
    </div>
</form>

@stop

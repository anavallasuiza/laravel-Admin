@extends('admin::layouts.right-side')

@section('content')
<div class="box">
    <div class="box-body">
        <form method="get" class="form-group">
            <input type="search" name="search" value="{{ Input::get('search-q') }}" class="form-control" placeholder="{{ __('Search') }}" />
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('User') }}</th>
                    <th class="text-center">{{ __('Admin') }}</th>
                    <th class="text-center">{{ __('Enabled') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($list as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>
                        <a href="{{ route('admin.management.users.edit', $row->id) }}">
                            {{ $row->name }}
                        </a>
                    </td>
                    <td>{{ $row->user }}</td>
                    <td class="text-center bg-{{ $row->admin ? 'success' : 'danger' }}">
                        {{ $row->admin ? __('Yes') : __('No') }}
                    </td>
                    <td class="text-center bg-{{ $row->enabled ? 'success' : 'danger' }}">
                        {{ $row->enabled ? __('Yes') : __('No') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="box-footer clearfix">
            <a href="{{ route('admin.management.users.edit') }}" class="pull-right btn btn-success">
                {{ __('New') }}
            </a>
        </div>

        @include('admin::molecules.pagination')
    </div>
</div>
@stop

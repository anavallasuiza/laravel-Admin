@extends('admin::layouts.right-side')

@section('content')

<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ __('Last orders')}}</h3>

                <div class="box-tools pull-right">
                    <a href="{{ route('admin::orders.index') }}" class="btn btn-sm">
                        {{ __('View all') }}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Import') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Paid') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $row)
                        <tr>
                            <td>
                                <a href="{{ route('admin::orders.edit', $row->id) }}">
                                    {{ $row->created_at }}
                                </a>
                            </td>
                            <td>{{ $row->total }}</td>
                            <td>{{ $row->shipment_name }}</td>
                            <td class="text-center bg-{{ $row->paid ? 'success' : 'danger' }}">
                                {{ $row->paid ? __('Yes') : __('No') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ __('Last registered users')}}</h3>

                <div class="box-tools pull-right">
                    <a href="{{ route('admin::users.index') }}" class="btn btn-sm">
                        {{ __('View all') }}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Signup') }}</th>
                            <th>{{ __('Name') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $row)
                        <tr>
                            <td>{{ $row->created_at }}</td>
                            <td>
                                <a href="{{ route('admin::users.edit', $row->id) }}">
                                    {{ $row->name }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop
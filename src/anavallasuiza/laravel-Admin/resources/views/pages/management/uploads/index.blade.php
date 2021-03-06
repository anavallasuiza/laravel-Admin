@extends('admin::layouts.right-side')

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-upload-file" data-toggle="tab">{{ __('Upload files') }}</a></li>
        <li><a href="#tab-upload-directory" data-toggle="tab">{{ __('Create directory') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab-upload-file">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_processor" value="fileNew" />

                <input type="file" name="files[]" multiple="true" class="file file-uploader" data-preview-file-type="any" />
            </form>
        </div>

        <div class="tab-pane" id="tab-upload-directory">
            <form method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden"  name="_processor" value="directoryNew" />

                <div class="input-group">
                    <input type="text" name="name" class="form-control" placeholder="{{ __('New directory') }}" required />

                    <span class="input-group-btn">
                        <button class="btn btn-primary">
                            {{ __('Create')}}
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<ol class="breadcrumb">
    <li>
        <a href="{{ route('admin.management.uploads.index') }}">
            <i class="fa fa-home"></i>
            {{ __('Home') }}
        </a>
    </li>

    @foreach ($location as $path)
    <li>
        <a href="{{ route('admin.management.uploads.index') }}?dir={{ $path['dir'] }}">
            <i class="fa fa-page"></i>
            {{ $path['name'] }}
        </a>
    </li>
    @endforeach
</ol>

@if ($directories || $files)

<table class="table datatable">
    <thead>
        <tr>
            <th class="text-center">{{ __('Name') }}</th>
            <th class="text-center">{{ __('Actions') }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($directories as $row)
        <tr>
            <td><a href="?dir={{ $row['dir'] }}"><strong>{{ $row['name'] }}</strong></a></td>
            <td class="text-center">
                <button type="button" name="uploads-delete" data-name="{{ $row['slug'] }}" data-_processor="directoryDelete" class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i>
                    <span class="sr-only sr-only-focusable">{{ __('Delete') }}</span>
                </button>
            </td>
        </tr>
        @endforeach

        @foreach ($files as $row)
        <tr>
            <td><a href="{{ $row['url'] }}" target="_blank">{{ $row['name'] }}</a></td>
            <td class="text-center">
                <button type="button" name="uploads-delete" data-name="{{ $row['slug'] }}" data-_processor="fileDelete" class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i>
                    <span class="sr-only sr-only-focusable">{{ __('Delete') }}</span>
                </button>
                <button type="button" name="uploads-copy-url" data-url="{{ $row['url'] }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-chain"></i>
                    <span class="sr-only sr-only-focusable">{{ __('Copy link') }}</span>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="uploads-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">{{ __('Close') }}</span>
                </button>

                <h4 class="modal-title">{{ __('Delete') }}</h4>
            </div>

            <div class="modal-body">
                <p>{{ __('Are you sure that you want to delete this content') }}</p>
            </div>

            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <input type="hidden" name="_processor" value="" />
                    <input type="hidden" name="name" value="" />

                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        {{ __('No, cancel') }}
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                        {{ __('Yes, Delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@else

<div class="callout callout-danger">
    <h4>{{ __('This folder is empty') }}</h4>
</div>

@endif

@stop

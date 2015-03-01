<div class="box-footer clearfix">
    <a href="{{ query('_action', 'downloadCSV') }}" class="pull-left btn btn-info">
        {{ __('Download CSV') }}
    </a>

    @if ($I->admin)
    <a href="{{ route('admin.'.$MODEL.'.edit') }}" class="pull-right btn btn-success">
        {{ __('New') }}
    </a>
    @endif
</div>
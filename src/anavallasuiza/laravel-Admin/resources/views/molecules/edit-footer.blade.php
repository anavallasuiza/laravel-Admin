<?php if (empty($I->admin)) return; ?>

<div class="box-footer clearfix">
    @if ($row->id)

    <div class="pull-right">
        <button type="submit" class="btn btn-success">
            {{ __('Save changes') }}
        </button>

        <button type="submit" name="_action" value="duplicate" class="btn btn-primary">
            {{ __('Duplicate') }}
        </button>
    </div>

    <div class="pull-left">
        <button type="button" data-toggle="modal" data-target="#modal-delete" class="btn btn-danger">
            {{ __('Delete') }}
        </button>

        <div class="modal fade" id="modal-delete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ __('Close') }}</span>
                        </button>

                        <h4 class="modal-title">{{ __('Are you sure do you want to delete this content?') }}</h4>
                    </div>

                    <div class="modal-body">
                        <p class="alert alert-warning text-center">
                            {{ __('Remember that this action cannot be undone') }}
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">
                            {{ __('No, cancel') }}
                        </button>

                        <button type="submit" name="_action" value="delete" class="btn btn-danger">
                            {{ __('Yes, delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else

    <div class="pull-right">
        <button type="submit" class="btn btn-success">
            {{ __('Create new') }}
        </button>
    </div>

    @endif
</div>
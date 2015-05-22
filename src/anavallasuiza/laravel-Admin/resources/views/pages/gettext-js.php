<?php use Admin\Library\Html; ?>

var gettext = {
    'Yes': '<?= __('Yes'); ?>',
    'No': '<?= __('No'); ?>',
    'copy-clipboard': '<?= __('Copy to clipboard: Ctrl+C and Enter'); ?>',
    'gettext-changes': '<?= __('This form was changed. Do you want to leave this page without save the changes?'); ?>',
    'datatables': {
        'sProcessing': '<?= Html::DT(__('datatables.sProcessing')); ?>',
        'sLengthMenu': '<?= Html::DT(__('datatables.sLengthMenu')); ?>',
        'sZeroRecords': '<?= Html::DT(__('datatables.sZeroRecords')); ?>',
        'sEmptyTable': '<?= Html::DT(__('datatables.sEmptyTable')); ?>',
        'sInfo': '<?= Html::DT(__('datatables.sInfo')); ?>',
        'sInfoEmpty': '<?= Html::DT(__('datatables.sInfoEmpty')); ?>',
        'sInfoFiltered': '<?= Html::DT(__('datatables.sInfoFiltered')); ?>',
        'sInfoPostFix': '<?= Html::DT(__('datatables.sInfoPostFix')); ?>',
        'sSearch': '<?= Html::DT(__('datatables.sSearch')); ?>',
        'sInfoThousands': '<?= Html::DT(__('datatables.sInfoThousands')); ?>',
        'sLoadingRecords': '<?= Html::DT(__('datatables.sLoadingRecords')); ?>',
        'oPaginate': {
            'sFirst': '<?= Html::DT(__('datatables.sFirst')); ?>',
            'sLast': '<?= Html::DT(__('datatables.sLast')); ?>',
            'sNext': '<?= Html::DT(__('datatables.sNext')); ?>',
            'sPrevious': '<?= Html::DT(__('datatables.sPrevious')); ?>'
        },
        'fnInfoCallback': null,
        'oAria': {
            'sSortAscending': '<?= Html::DT(__('datatables.sSortAscending')); ?>',
            'sSortDescending': '<?= Html::DT(__('datatables.sSortDescending')); ?>'
        }
    }
};

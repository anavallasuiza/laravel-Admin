<?php if (!($flash = Session::get('flash-message'))) {
    return;
} ?>

@include('admin::molecules.alert', [
    'status' => $flash['status'],
    'message' => $flash['message']
])

<?php Session::forget('flash-message') ?>

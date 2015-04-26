<?php use Admin\Http\Controllers\Forms\Form;

?>

@extends('admin::layouts.master')

@section('body')
<div class="login-page">
    <div class="login-box">
        <div class="login-logo">{{ __('Sign In') }}</div>

        <div class="login-box-body">
            @include('admin::molecules.alert-flash')

            <form method="post" class="clearfix">
                {!! $form->tokenAndFake() !!}
                {!! $form->html() !!}

                <div class="pull-right">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ __('Sign me in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

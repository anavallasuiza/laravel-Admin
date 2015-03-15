@extends('admin::layouts.in')

@section('right-side')

<section class="content-header">
    <h1>{{ Meta::meta('title') }}</h1>
</section>

<section class="content">
    @include('admin::molecules.alert-flash')
    @yield('content')
</section>

@stop

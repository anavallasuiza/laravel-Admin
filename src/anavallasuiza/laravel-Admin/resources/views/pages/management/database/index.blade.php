@extends('admin::layouts.right-side')

@section('content')

<pre><p>{!! implode('</p><p>', $sql) !!}</p></pre>

@stop

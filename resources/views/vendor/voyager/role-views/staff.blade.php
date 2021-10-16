@extends('voyager::master')
@section('content')
<body>
  Dashboard for
  @php
    echo Auth::user()->role->name;
  @endphp
</body>
@stop
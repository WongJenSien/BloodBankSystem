

@extends('BackEnd.app')

@section('content')
@if (session('status'))
<h4>{{session('status')}}</h4>
@endif
@endsection
@extends('BackEnd.app')

@section('content')

@if(session('status'))
<h4 class="alert alert-warning mb-2">{{session('status')}}</h4>
@endif

@endsection
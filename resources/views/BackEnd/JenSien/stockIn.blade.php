@extends('BackEnd.app')

@section('content')

<table class="table table-border">
    <thead>
        <th>Stock IN page</th>
    </thead>
</table>



<form action="{{url('add-inventory')}}" method="POST">
    @csrf
    <input type="text" name="name" id="name" class="form-control my-4 py-2"
        placeholder="Name">
    <input type="password" name="password" id="password" class="form-control my-4 py-2"
        placeholder="Password">
    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@endsection
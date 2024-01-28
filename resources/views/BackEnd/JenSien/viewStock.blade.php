

@extends('BackEnd.app')

@section('content')
{{-- @if (session('status'))
<h4>{{session('status')}}</h4>
@endif --}}

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Password</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reference as $key => $item)
            <tr>
                <td>
                    {{$item['name']}}
                </td>
                <td>
                    {{$item['password']}}
                </td>
            </tr>
        @empty
            <tr>
                <td>No record</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
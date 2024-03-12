@extends('BackEnd.app')

@section('content')
    <h2 style="color: black;font-weight:bold;">Event List</h2>

    <a href="{{ route('addEventForm') }}" class="btn btn-primary">Add Event</a>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10" style="background-color: pink;border-radius:33px;min-height:600px;">
            <div>&nbsp;</div>
            <table class="table table-bordered" style="color:black;">
                <tr>
                    <th>Name</th>
                    <th>Venue</th>
                    <th style="width: 300px;">Action</th>
                </tr>
                @foreach ($paginationData as $key => $value)
                <tr>
                    <td>{{ $value['eventName'] }}</td>
                    <td>{{ $value['eventVenue'] }}</td>
                    <td>
                        <a href="{{ route('updateEventForm', ['key' => $key]) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('viewEventForm', ['key' => $key]) }}" class="btn btn-primary">View</a>
                        <a href="{{ route('deleteEvent', ['key' => $key]) }}" class="btn btn-primary" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                        <a href="{{ route('eventReport', ['key' => $key]) }}" class="btn btn-primary">Report</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="col-1">&nbsp;</div>
    </div>

    {{ $paginationData->links() }}
@endsection

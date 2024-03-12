@extends('BackEnd.app')

@section('content')
    <style>
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }
    </style>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10" style="background-color: pink;border-radius:33px;min-height:600px;">
            <div style="text-align: center;">
                <img src="{{ asset('storage/' . $record['path']) }}" class="profile-image" />
            </div>

            <div>&nbsp;</div>

            <table class="table table-bordered" style="background-color:white;color:black;">
                <tr>
                    <td>Event Name</td>
                    <td>:</td>
                    <td>{{ $record['eventName'] }}</td>
                </tr>
                <tr>
                    <td>Sponsor</td>
                    <td>:</td>
                    <td>{{ $record['eventSponsor'] }}</td>
                </tr>
                <tr>
                    <td>Event Venue</td>
                    <td>:</td>
                    <td>{{ $record['eventVenue'] }}</td>
                </tr>
                <tr>
                    <td>Event Start Date</td>
                    <td>:</td>
                    <td>{{ $record['eventStartDate'] }}</td>
                </tr>
                <tr>
                    <td>Event End Date</td>
                    <td>:</td>
                    <td>{{ $record['eventEndDate'] }}</td>
                </tr>
                <tr>
                    <td>Event Start Time</td>
                    <td>:</td>
                    <td>{{ $record['eventStartTime'] }}</td>
                </tr>
                <tr>
                    <td>Event End Time</td>
                    <td>:</td>
                    <td>{{ $record['eventEndTime'] }}</td>
                </tr>
            </table>
        </div>
        <div class="col-1">&nbsp;</div>
    </div>
@endsection

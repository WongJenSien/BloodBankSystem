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
                    <td>Name</td>
                    <td>:</td>
                    <td>{{ $record['name'] }}</td>
                </tr>
                <tr>
                    <td>IC</td>
                    <td>:</td>
                    <td>{{ $record['identityCard'] }}</td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td>:</td>
                    <td>{{ $record['BOD'] }}</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>:</td>
                    <td>{{ $record['gender'] }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td>{{ $record['contactNumber'] }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $record['emailAddress'] }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td>{{ $record['address'] }}</td>
                </tr>
                <tr>
                    <td>Postcode</td>
                    <td>:</td>
                    <td>{{ $record['postcode'] }}</td>
                </tr>
            </table>
        </div>
        <div class="col-1">&nbsp;</div>
    </div>
@endsection

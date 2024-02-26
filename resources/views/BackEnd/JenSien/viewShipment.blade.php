@extends('BackEnd.app')

@section('content')
    @if (session('status'))
        <h4 class="alert alert-warning mb-2">{{ session('status') }}</h4>
    @endif

    <div class="container m-3 border border-black">
        <div class="row">
            <div class="row">
                <h3>Recent Activity</h3>
            </div>
            <div class="row">
                <table class="border border-black p-3 m-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Shipment ID</th>
                            <th>Date</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Name</td>
                            <td>S2402001</td>
                            <td>01-02-2024</td>
                            <td>Ship</td>
                            <td><a href="#">View Details</a></td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <div class="container m-3 border border-black">
        <div class="row">
            <div class="row">
                <h3>Shipment List</h3>
            </div>
            <div class="row">
                <table class="border border-black p-3 m-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Shipment ID</th>
                            <th>Request Date</th>
                            <th>Location</th>
                            <th>Shipment Date</th>
                            <th>Status</th>
                            <th>Total Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipInfo as $key)
                            <tr>
                                <td>{{ $loop->iteration }}</td> 
                                <td>{{$key['ShipID']}}</td>
                                <td>{{$key['RequestDate']}}</td>
                                <td>{{$key['location']}}</td>
                                <td>{{$key['ShipDate']}}</td>
                                <td>{{$key['Status']}}</td>
                                <td><a href="#">View Details</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>
    </div>
@endsection

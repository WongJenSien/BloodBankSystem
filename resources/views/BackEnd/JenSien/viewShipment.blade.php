@extends('BackEnd.app')

@section('content')
    @if (session('status'))
        <h4 class="alert alert-warning mb-2">{{ session('status') }}</h4>
    @endif


    <div class="container">
        {{-- VIEW -- ROW 1 --}}
        <div class="row">
            <div class="container mx-auto mt-5">
                <div class="row text-start">
                    <h2 class="text-black">Recent Activity</h2>
                </div>
                <div class="row">
                    <table class="table table-hover mt-2">
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

        {{-- VIEW -- ROW 2 --}}
        <div class="row">
            <div class="container text-center mx-auto border border-black mt-3">
                <div class="row text-start">
                    <h2 class="text-black">Shipment List</h2>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="row">
                            {{-- LEFT SIDE VIEW --}}
                            <div class="col-3 border border-black ml-1">
                                <div class="row">
                                    <div class="container border border-danger">
                                        {{-- COL-1 --}}
                                        <div class="row border border-black p-1 m-1">
                                            <table class="border border-black p-1 m-1 text-black">
                                                <thead>
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Pending</td>
                                                        <td>123</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Shipment</td>
                                                        <td>123</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Delivered</td>
                                                        <td>123</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total</th>
                                                        <td>123</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- RIGHT SIDE VIEW --}}
                            <div class="col border border-black ml-2 ">
                                <div class="row">
                                    <div class="container inv-activity-container">
                                        <div class="row inv-activity-fixed">
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
                                                            <td>{{ $key['ShipID'] }}</td>
                                                            <td>{{ $key['RequestDate'] }}</td>
                                                            <td>{{ $key['location'] }}</td>
                                                            <td>{{ $key['ShipDate'] }}</td>
                                                            <td>{{ $key['Status'] }}</td>
                                                            <td><a href="shipment-view-detials/{{$key['ShipID']}}">View Details</a></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

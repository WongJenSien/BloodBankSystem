@extends('BackEnd.app')

@section('content')
    @if (session('status'))
    <div class="alert alert-success">
        {{session('status')}}
    </div>
    @endif

    <div class="container">

        <div class="row">
            <h4>Shipment Information</h4>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border">Shipment ID</div>
            <div class="col p-2 border text-start"><span class="m-2">{{ $shipmentID }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border ">Request Date</div>
            <div class="col p-2 border text-start"><span class="m-2">{{ $shipInfo['RequestDate'] }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border ">Shipment Date</div>
            <div class="col p-2 border text-start"><span class="m-2">{{ $shipInfo['ShipDate'] }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border ">Location</div>
            <div class="col p-2 border text-start"><span class="m-2">{{ $shipInfo['location'] }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border">Description</div>
            <div class="col p-2 border text-start"><span class="m-2">{{ $shipInfo['Description'] }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border ">Products</div>
            <div class="col p-2 border text-start">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>Blood Type</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($shipInfo['Quantity'] as $key => $value)
                            <tr>
                                <td class="text-center">
                                    @if ($key === 'aPositive')
                                        A+
                                    @elseif($key === 'aNegative')
                                        A-
                                    @elseif($key === 'bPositive')
                                        B+
                                    @elseif($key === 'bNegative')
                                        B-
                                    @elseif($key === 'oPositive')
                                        O+
                                    @elseif($key === 'oNegative')
                                        O-
                                    @elseif($key === 'abPositive')
                                        AB+
                                    @elseif($key === 'abNegative')
                                        AB-
                                    @endif
                                </td>
                                <td>
                                    {{ $value }}
                                    @php
                                        $total += $value;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td>{{ $total }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border ">Status</div>
            <div class="col p-2 border text-start">
                <span class="m-2">{{ $shipInfo['Status'] }}</span>
                <a data-toggle="modal" data-target="#updateShipment"
                    style="text-decoration: underline; cursor:pointer">Update</a>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <div class="row">
            <h4>Package Information</h4>
        </div>
        <div class="row mt-2">
            <table class="table table-bordered table-hover">
                <thead>
                    <th>No</th>
                    <th>Blood ID</th>
                    <th>Blood Type</th>
                    <th>Expirated Date</th>
                    <th>Inventory ID</th>
                    <th>Event Name</th> 
                </thead>
                <tbody>
                    @foreach ($bloodList as $key => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $key }}
                            </td>
                            <td>
                                @if ($item['bloodType'] === 'aPositive')
                                    A+
                                @elseif($item['bloodType'] === 'aNegative')
                                    A-
                                @elseif($item['bloodType'] === 'bPositive')
                                    B+
                                @elseif($item['bloodType'] === 'bNegative')
                                    B-
                                @elseif($item['bloodType'] === 'oPositive')
                                    O+
                                @elseif($item['bloodType'] === 'oNegative')
                                    O-
                                @elseif($item['bloodType'] === 'abPositive')
                                    AB+
                                @elseif($item['bloodType'] === 'abNegative')
                                    AB-
                                @endif
                            </td>
                            <td>{{ $item['expirationDate'] }}</td>
                            <td>{{ $item['inventoryID'] }}</td>
                            <td>{{ $item['eventName'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    {{-- Update Model --}}
    <div class="modal fade" id="updateShipment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Shipment Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('shipment-edit-status') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                Shipment ID : {{ $shipmentID }}
                            </div>
                            <div class="row  m-2">
                                <div role="group" aria-label="Basic example">
                                    <input type="hidden" name="shipID" value="{{ $shipmentID }}" />

                                    <input type="radio" class="btn-check" name="status" id="pending" value="Pending"
                                        {{ $shipInfo['Status'] == 'Pending' ? 'checked' : '' }}>
                                    <label class="btn btn-secondary shipment-status-model" for="pending">pending</label>

                                    <input type="radio" class="btn-check" name="status" id="delivering"
                                        value="Delivering" {{ $shipInfo['Status'] == 'Delivering' ? 'checked' : '' }}>
                                    <label class="btn btn-secondary shipment-status-model"
                                        for="delivering">delivering</label>

                                    <input type="radio" class="btn-check" name="status" id="shiped" value="Shipped"
                                        {{ $shipInfo['Status'] == 'Shipped' ? 'checked' : '' }}>
                                    <label class="btn btn-secondary shipment-status-model" for="shiped">shiped</label>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

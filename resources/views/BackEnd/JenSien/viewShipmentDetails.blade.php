@extends('BackEnd.app')

@section('content')
    <div class="container">
        <div class="row">
            <h4>Shipment Information</h4>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Shipment ID</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['ShipID'] }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Request Date</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['RequestDate'] }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Shipment Date</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['ShipDate'] }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Location</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['location'] }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Description</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['Description'] }}</span></div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Products</div>
            <div class="col p-2 border border-black text-start">
                <table class="table table-border table-hover text-center">
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
                        @foreach($shipInfo['Quantity'] as $key => $value)
                        <tr>
                            <td class="text-center">
                                @if($key === 'aPositive') A+
                                @elseif($key === 'aNegative') A-

                                @elseif($key === 'bPositive') B+
                                @elseif($key === 'bNegative') B-

                                @elseif($key === 'oPositive') O+
                                @elseif($key === 'oNegative') O-

                                @elseif($key === 'abPositive') AB+
                                @elseif($key === 'abNegative') AB-

                                @endif
                            </td>
                            <td>
                                {{$value}}
                                @php
                                    $total+=$value;
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td>{{$total}}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-3 p-2 border border-black">Status</div>
            <div class="col p-2 border border-black text-start"><span class="m-2">{{  $shipInfo['Status'] }}</span></div>
        </div>
    </div>
    <div class="container m-3">
        <div class="row">
            <h4>Package Information</h4>
        </div>
        <div class="row">
            <table class="table border border-black table-hover">
                <thead>
                    <th>No</th>
                    <th>Blood ID</th>
                    <th>Blood Type</th>
                    <th>Expirated Date</th>
                    <th>Inventory ID</th>
                </thead>
                <tbody>
                    @foreach ($listInfo as $key => $item)
                    
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            {{$key}}
                        </td>
                        <td>
                            @if($item['bloodType'] === 'aPositive') A+
                                @elseif($item['bloodType'] === 'aNegative') A-

                                @elseif($item['bloodType'] === 'bPositive') B+
                                @elseif($item['bloodType'] === 'bNegative') B-

                                @elseif($item['bloodType'] === 'oPositive') O+
                                @elseif($item['bloodType'] === 'oNegative') O-

                                @elseif($item['bloodType'] === 'abPositive') AB+
                                @elseif($item['bloodType'] === 'abNegative') AB-
                            @endif
                        </td>
                        <td>{{$item['expirationDate']}}</td>
                        <td>{{$item['inventoryID']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


{{-- <div class="container">
    <div class="row">
        <div class="col col-sm-2 border border-black p-2 ">Shipment ID</div>
        <div class="col border border-black p-2 text-start">
            <span class="p-2">{{ $shipmentID }}</span>
        </div>
    </div>
    <div class="row">
        <div class="col col-sm-2 border border-black p-2 ">Inventory ID</div>
        <div class="col border border-black p-2 text-start">
            <div class="row">
                @foreach ($inventoryInfo as $key => $value)
                    <div class="col">
                        <span class="p-2">{{ $key }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-sm-2 border border-black p-2 ">Event Info</div>
        <div class="col border border-black p-2 text-start">
            <div class="row">
                @foreach ($eventInfo as $key => $value)
                    <div class="col">
                        <span class="p-2">{{ $key }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div> --}}

{{-- display package list --}}
{{-- <div class="container">
    <div class="row">
        <h5>Package List Info</h5>
    </div>
    <div class="row">
        <table class="table border-black border">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Blood Type</th>
                    <th>Expirated Date</th>
                </tr>
            </thead>
        </table>
    </div>
</div> --}}

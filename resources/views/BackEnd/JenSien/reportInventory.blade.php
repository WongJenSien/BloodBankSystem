@extends('BackEnd.app')
@section('content')
    {{-- TOP VIEW --}}
    <div class="container m-4">
        <div class="row">
            <div class="col ">
                <a class="btn p-2 m-3 {{ Request::is('inventory-report') ? 'selected-btn' : 'non-selected-btn' }}"
                    href="{{ url('inventory-report') }}" role="button">Inventory Report</a>
                <a class="btn  p-2 m-3 {{ Request::is('shipment-report') ? 'selected-btn' : 'non-selected-btn' }}"
                    href="{{ url('shipment-report') }}" role="button">Shipment Report</a>
            </div>
        </div>
    </div>

    {{-- TOP 5 STOCK IN --}}
    <div class="container  align-items-center">
        <div class="container">
            <div class="row">
                <div class="col text-start">
                    <span class="h3 text-dark text-start font-weight-bold">TOP 5 STOCK IN - FOR {{$month}}</span>
                </div>
                <div class="col-2 text-center">
                    <a class="btn h3" href="{{ url('download-inventory-report') }}" role="button"><i
                            class="bi bi-box-arrow-in-down h3"></i>Download </a>
                </div>
            </div>

            <div class="row">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="align-middle" rowspan="2">Inventory ID</th>
                            <th class="align-middle" rowspan="2">Event ID</th>
                            <th class="align-middle" rowspan="2">Event Name</th>
                            <th class="align-middle" colspan="4">Quantity</th>
                            <th class="align-middle" colspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>A</th>
                            <th>B</th>
                            <th>O</th>
                            <th>AB</th>
                            <th>Available</th>
                            <th>Not Available</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($stockInList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['eventID'] }}</td>
                                <td>{{ $item['EventName'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeA'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeB'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeO'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeAB'] }}</td>
                                <td>{{ $item['StatusQuantity']['Available'] }}</td>
                                <td>{{ $item['StatusQuantity']['Shipment'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row ">
                <div class="container">
                    <div class="row">
                        <span class="h3 text-dark text-start text-decoration-underline">
                            Summary
                        </span>
                    </div>
                    <div class="row">
                        {{-- COUNT TOTAL NUMBER OF BLOOD RECEIVE FOR THE MONTH --}}
                        <div class="col">
                            <div class="container border border-black">
                                <div class="row">
                                    <span class="text-dark text-decoration-underline h5 font-weight-bold m-1 p-1">
                                        Total Number of Blood Receive
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        {{-- VIEW BLOOD - A -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            {{-- TITLE --}}
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type A
                                                </span>
                                            </div>
                                            {{-- QUANTITY --}}
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['aPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['aNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - B -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type B
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['bPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['bNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- VIEW BLOOD - O -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type O
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['oPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['oNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - AB -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type AB
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['abPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood['abNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- COUNT TOTAL NUMBER OF BLOOD AVAILABLE FOR THE MONTH --}}
                        <div class="col">
                            <div class="container border border-black">
                                <div class="row">
                                    <span class="text-dark text-decoration-underline h5 font-weight-bold p-1 m-1">
                                        Total Number of Blood Available
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        {{-- VIEW BLOOD - A -- TOTAL NUMBER OF AVAILABLE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            {{-- TITLE --}}
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type A
                                                </span>
                                            </div>
                                            {{-- QUANTITY --}}
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['aPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['aNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - B -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type B
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['bPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['bNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- VIEW BLOOD - O -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type O
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['oPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['oNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - AB -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type AB
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['abPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_available['abNegative'] }}
                                                    </span>
                                                </span>
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
    </div>
    <hr>
    {{-- TOP 5 STOCK OUT --}}
    <div class="container  align-items-center">
        <div class="container">
            <div class="row">
                <div class="col text-start">
                    <span class="h3 text-dark text-start font-weight-bold">TOP 5 STOCK OUT - FOR {{$month}}</span>
                </div>
            </div>
            <div class="row">
                <table class="table table-bordered table-hover text-center align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="align-middle" rowspan="2">Shipment ID</th>
                            <th class="align-middle" rowspan="2">Request Date</th>
                            <th class="align-middle" rowspan="2">Location</th>
                            <th class="align-middle" rowspan="2">Shipment Date</th>
                            <th class="align-middle" rowspan="2">Status</th>
                            <th class="align-middle" colspan="4">Quantity</th>
                        </tr>
                        <tr>
                            <th>A</th>
                            <th>B</th>
                            <th>O</th>
                            <th>AB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOutList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['RequestDate'] }}</td>
                                <td>{{ $item['location'] }}</td>
                                <td>{{ $item['ShipDate'] }}</td>
                                <td>{{ $item['Status'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeA'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeB'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeO'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeAB'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row ">
                <div class="container">
                    <div class="row">
                        <span class="h3 text-dark text-start text-decoration-underline">
                            Summary
                        </span>
                    </div>
                    <div class="row">
                        {{-- COUNT TOTAL NUMBER OF BLOOD RECEIVE FOR THE MONTH --}}
                        <div class="col">
                            <div class="container border border-black">
                                <div class="row">
                                    <span class="text-dark text-decoration-underline h5 font-weight-bold m-1 p-1">
                                        Total Number of Blood Shipped
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="row">
                                        {{-- VIEW BLOOD - A -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            {{-- TITLE --}}
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type A
                                                </span>
                                            </div>
                                            {{-- QUANTITY --}}
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['aPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['aNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - B -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type B
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['bPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['bNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- VIEW BLOOD - O -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type O
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['oPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['oNegative'] }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- VIEW BLOOD - AB -- TOTAL NUMBER OF RECIEVE --}}
                                        <div class="col p-3 m-3 alignment-center">
                                            <div class="row">
                                                <span
                                                    class="text-decoration-underline text-danger font-weight-bold text-center">
                                                    Blood Type AB
                                                </span>
                                            </div>
                                            <div class="row">
                                                <span class="text-dark font-weight-bold"> Positive: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['abPositive'] }}
                                                    </span>
                                                </span>
                                                <span class="text-dark font-weight-bold"> Negative: <span
                                                        class="text-dark font-weight-normal">
                                                        {{ $numOfBlood_Shipped['abNegative'] }}
                                                    </span>
                                                </span>
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
    </div>
@endsection

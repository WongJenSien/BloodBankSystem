@extends('BackEnd.app')
@section('content')
    {{-- TOP VIEW --}}
    <div class="container">
        <div class="row">
            <div class="col">
                <a class="btn {{ Request::is('inventory-report') ? 'selected-btn' : 'non-selected-btn' }}"
                    href="{{ url('inventory-report') }}" role="button">Inventory Report</a>
            </div>
            <div class="col">
                <a class="btn {{ Request::is('shipment-report') ? 'selected-btn' : 'non-selected-btn' }}"
                    href="{{ url('shipment-report') }}" role="button">Shipment Report</a>
            </div>
        </div>

    </div>


    {{-- TOP 5 STOCK IN --}}
    <div class="container">
        <div class="row">
            <p class="h1">TOP 5 STOCK IN</p>
        </div>
        <table class="table  table-bordered hover text-center align-middle">
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
                <tr>
                    <td>1</td>
                    <td>I2402001</td>
                    <td>E2402001</td>
                    <td>Blood donation</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- TOP 5 STOCK OUT --}}
    <div class="container">
        <div class="row">
            <p class="h1">TOP 5 STOCK OUT</p>
        </div>
        <table class="table  table-bordered hover text-center align-middle">
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
                <tr>
                    <td>1</td>
                    <td>I2402001</td>
                    <td>E2402001</td>
                    <td>Blood donation</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                    <td>12</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

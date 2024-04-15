@extends('BackEnd.app')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.show-modal').click(function() {
                var inventoryID = $('#inventoryID').val();
                var modalID = '#editStock' + inventoryID;
                $(modalID).modal('show');
            });
        });
    </script>

    @if ($inventoryInfo == null)
        <div class="row">
            <span class="h4 text-center text-dark">
                No Record Found...<br>Please insert record [<a href="{{ url('add-inventory') }}">Click Here</a>]
            </span>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <form action="{{ url('getInventoryID') }}" method="post">
                            @csrf
                            <div class="col">
                                <div class="mb-3 row">
                                    <div class="col-sm-2">
                                        <label for="inventoryID" class="col-form-label">Inventory ID: </label>
                                    </div>
                                    <div class="col-sm-2 text-start">
                                        <select name="x" id="inventoryID" class="form-select"
                                            style="width: fit-content" data-width="fit" aria-label="Default select example">
                                            <option value="default">Select Inventory ID</option>
                                            @foreach ($inventoryInfo as $key => $value)
                                                <option value="{{ $key }}">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4 d-flex">
                                        <button class="btn btn-primary show-modal" type="button">Show</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <hr>
            </div>
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan='2'>No</th>
                            <th class="align-middle" rowspan='2'>Inventory ID</th>
                            <th class="align-middle" rowspan='2'>Date</th>
                            <th class="align-middle" rowspan='2'>Event Name</th>
                            <th colspan="4">Quantity (Blood Type)</th>
                            <th class="align-middle" rowspan='2'>Edit</th>
                        </tr>
                        <tr>
                            <th>A</th>
                            <th>B</th>
                            <th>O</th>
                            <th>AB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventoryList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['date'] }}</td>
                                <td>{{ $item['eventName'] }}</td>
                                <td>{{ $item['bloodType']['BloodType_A'] }}</td>
                                <td>{{ $item['bloodType']['BloodType_B'] }}</td>
                                <td>{{ $item['bloodType']['BloodType_O'] }}</td>
                                <td>{{ $item['bloodType']['BloodType_AB'] }}</td>
                                <td><a data-toggle="modal" data-target="#editStock{{ $key }}"
                                        style="text-decoration: underline; cursor:pointer; color:blue;">Result</a></td>

                            </tr>

                            {{-- MODEL --}}
                            <div class="modal fade bd-example-modal-lg" id="editStock{{ $key }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header p-3 m-3">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Model</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ url('edit-inventory') }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" id="invoiceDate" name="invoiceDate"
                                                onclick="dateRange(true)" value="{{ $item['date'] }}">
                                            <div class="container">
                                                {{-- InventoryID ROW --}}
                                                <div class="row">
                                                    <div class="mb-3 row">
                                                        <label for="inventoryID" class="col-sm-2 col-form-label"> ID:
                                                        </label>
                                                        <div class="col-sm-10 text-start">
                                                            <input type="text" readonly
                                                                class="form-control-plaintext fw-bold" name="inventoryID"
                                                                id="inventoryID" value="{{ $key }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Date ROW --}}
                                                <div class="row">
                                                    <div class="mb-3 row">
                                                        <label for="inventoryID" class="col-sm-2 col-form-label"> Date:
                                                        </label>
                                                        <div class="col-sm-10 text-start">
                                                            <input type="text" readonly
                                                                class="form-control-plaintext fw-bold" name="inventoryDate"
                                                                id="inventoryDate" value="{{ $item['date'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- EventID ROW --}}
                                                <div class="row">
                                                    <div class="mb-3 row p-2">
                                                        <label for="eventID" class="col-sm-2 col-form-label">Event:
                                                        </label>
                                                        <div class="col-sm-10 text-start">
                                                            <select name="eventID" id="eventID" class="form-select"
                                                                style="width: fit-content" data-width="fit"
                                                                aria-label="Default select example">
                                                                <option value="default" selected>Select an Event</option>
                                                                @foreach ($eventInfo as $eventKey => $value)
                                                                    <option value="{{ $eventKey }}"
                                                                        {{ $item['eventID'] == $eventKey ? 'selected' : '' }}>
                                                                        {{ $value['eventName'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        {{-- Blood Type A --}}
                                                        <div class="row m-3">
                                                            <div class="row">
                                                                <span class="h3 text-start font-weight-bold text-danger">
                                                                    Blood Type - A
                                                                </span>
                                                            </div>
                                                            {{-- Positive --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: A+
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="aPositive"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="aPositive" id="aPositive"
                                                                                    min="{{ $item['shipQuantity']['aPositive'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['aPositive'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_A_P"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_A_P"
                                                                                    id="expiredDate_A_P"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['aPositive'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Negative --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: A-
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="aNegative"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="aNegative" id="aNegative"
                                                                                    min="{{ $item['shipQuantity']['aNegative'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['aNegative'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_A_N"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_A_N"
                                                                                    id="expiredDate_A_N"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['aNegative'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Blood Type B --}}
                                                        <div class="row m-3 ">
                                                            <div class="row">
                                                                <span class="h3 text-start font-weight-bold text-danger">
                                                                    Blood Type - B
                                                                </span>
                                                            </div>
                                                            {{-- Positive --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: B+
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="bPositive"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="bPositive" id="bPositive"
                                                                                    min="{{ $item['shipQuantity']['bPositive'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['bPositive'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_B_P"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_B_P"
                                                                                    id="expiredDate_B_P"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['bPositive'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Negative --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: B-
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="bNegative"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="bNegative" id="bNegative"
                                                                                    min="{{ $item['shipQuantity']['bNegative'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['bNegative'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_B_N"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_B_N"
                                                                                    id="expiredDate_B_N"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['bNegative'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Blood Type O --}}
                                                        <div class="row m-3 ">
                                                            <div class="row">
                                                                <span class="h3 text-start font-weight-bold text-danger">
                                                                    Blood Type - O
                                                                </span>
                                                            </div>
                                                            {{-- Positive --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: O+
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="oPositive"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="oPositive" id="oPositive"
                                                                                    min="{{ $item['shipQuantity']['oPositive'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['oPositive'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_O_P"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_O_P"
                                                                                    id="expiredDate_O_P"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['oPositive'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Negative --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: O-
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="oNegative"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="oNegative" id="oNegative"
                                                                                    min="{{ $item['shipQuantity']['oNegative'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['oNegative'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_O_N"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_O_N"
                                                                                    id="expiredDate_O_N"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['oNegative'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Blood Type AB --}}
                                                        <div class="row m-3 ">
                                                            <div class="row">
                                                                <span class="h3 text-start font-weight-bold text-danger">
                                                                    Blood Type - AB
                                                                </span>
                                                            </div>
                                                            {{-- Positive --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: AB+
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="abPositive"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="abPositive" id="abPositive"
                                                                                    min="{{ $item['shipQuantity']['abPositive'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['abPositive'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_AB_P"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_AB_P"
                                                                                    id="expiredDate_AB_P"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['abPositive'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Negative --}}
                                                            <div class="container p-2 m-2">
                                                                <div class="row">
                                                                    <span class="h4 text-start font-weight-bold text-dark">
                                                                        Blood Type: AB-
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group row">
                                                                            <label for="abNegative"
                                                                                class="col-sm-2 col-form-label">Quantity:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="number"
                                                                                    name="abNegative" id="abNegative"
                                                                                    min="{{ $item['shipQuantity']['abNegative'] }}"
                                                                                    max="10000"
                                                                                    value="{{ $item['quantity']['abNegative'] }}"
                                                                                    onchange="countBlood()" />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="expiredDate_AB_N"
                                                                                class="col-sm-2 col-form-label">Expired
                                                                                Date:
                                                                            </label>
                                                                            <div class="col-sm-10">
                                                                                <input class="form-control" type="date"
                                                                                    name="expiredDate_AB_N"
                                                                                    id="expiredDate_AB_N"
                                                                                    onclick="dateRange(true)"
                                                                                    value="{{ $item['expirationDate']['abNegative'] }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-3">
                                                    <button type="submit" class="p-2 btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col">
                                                <div class="row">

                                                    <table class="table table-bordered align-item-middle">
                                                        <thead>
                                                            <th colspan="2">Category</th>
                                                            <th>Quantity</th>
                                                            <th>Expired Date</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td rowspan="2"><b>A</b></td>
                                                                <td>A+</td>
                                                                <td><input type="number" name="aPositive" id="aPositive"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['aPositive'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_A_P"
                                                                        id="expiredDate_A_P" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['aPositive'] }}" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>A-</td>
                                                                <td><input type="number" name="aNegative" id="aNegative"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['aNegative'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_A_N"
                                                                        id="expiredDate_A_N" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['aNegative'] }}" />
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td rowspan="2"><b>B</b></td>
                                                                <td>B+</td>
                                                                <td><input type="number" name="bPositive" id="bPositive"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['bPositive'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_B_P"
                                                                        id="expiredDate_B_P" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['bPositive'] }}" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>B-</td>
                                                                <td><input type="number" name="bNegative" id="bNegative"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['bNegative'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_B_N"
                                                                        id="expiredDate_B_N" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['bNegative'] }}" />
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td rowspan="2"><b>O</b></td>
                                                                <td>O+</td>
                                                                <td><input type="number" name="oPositive" id="oPositive"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['oPositive'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_O_P"
                                                                        id="expiredDate_O_P" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['oPositive'] }}" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>O-</td>
                                                                <td><input type="number" name="oNegative" id="oNegative"
                                                                        min="0" max="10000"
                                                                        value="{{ $item['quantity']['oNegative'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_O_N"
                                                                        id="expiredDate_O_N" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['oNegative'] }}" />
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td rowspan="2"><b>AB</b></td>
                                                                <td>AB+</td>
                                                                <td><input type="number" name="abPositive"
                                                                        id="abPositive" min="0" max="10000"
                                                                        value="{{ $item['quantity']['abPositive'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_AB_P"
                                                                        id="expiredDate_AB_P" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['abPositive'] }}" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>AB-</td>
                                                                <td><input type="number" name="abNegative"
                                                                        id="abNegative" min="0" max="10000"
                                                                        value="{{ $item['quantity']['abNegative'] }}"
                                                                        onchange="countBlood()" /></td>
                                                                <td><input type="date" name="expiredDate_AB_N"
                                                                        id="expiredDate_AB_N" onclick="dateRange(true)"
                                                                        value="{{ $item['expirationDate']['abNegative'] }}" />
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col col-lg-2">
                                                <table class="table table-bordered mx-auto table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Blood Type</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>A</td>
                                                            <td><label id="labelA">0</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td>B</td>
                                                            <td><label id="labelB">0</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td>O</td>
                                                            <td><label id="labelO">0</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td>AB</td>
                                                            <td><label id="labelAB">0</label></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> --}}

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- END --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

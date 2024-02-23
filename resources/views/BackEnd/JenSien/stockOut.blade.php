@extends('BackEnd.app')

@section('content')
    <div class="container">
        <form action="{{ url('remove-inventory') }}" method="POST">
            @csrf
            <div class="row">

                {{-- COL - 1 --}}
                <div class="col border border-danger text-middle align-middle mx-auto">
                    <div class="form-group row border border-black">
                        <label for="ship-id" class="col-sm-4 col-form-label border border-black">Shipment ID:</label>
                        <div class="col-sm-8 border border-black">
                            <input type="text" name="ship_id" id="ship_id" class="form-control-plaintext"
                                value="{{ $shipmentID }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row border border-black">
                        <label for="ship-today" class="col-sm-4 col-form-label border border-black">Request Date:</label>
                        <div class="col-sm-8 border border-black">
                            <input type="date" name="ship_today" id="ship_today" class="form-control-plaintext"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row border border-black">
                        <label for="ship-location" class="col-sm-4 col-form-label border border-black">Location:</label>
                        <div class="col-sm-8 border border-black">
                            <select name="location" class="form-select" aria-label="Default select example">
                                <option selected>Select an Location</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row border border-black">
                        <label for="ship-date" class="col-sm-4 col-form-label border border-black">Shipment Date:</label>
                        <div class="col-sm-8 border border-black">
                            <input class="form-control" type="date" name="ship_date" id="ship_date"
                                onclick="getShipDate()" value="{{ date('Y-m-d') }}" />
                        </div>
                    </div>

                    <div class="form-group row border border-black">
                        <label for="ship-description"
                            class="col-sm-4 col-form-label border border-black">Description:</label>
                        <div class="col-sm-8 border border-black">
                            <textarea class="form-control" id="description" name="description" rows="7" placeholder="Type here..."></textarea>
                        </div>
                    </div>

                </div>

                {{-- COL - 2 --}}
                <div class="col-4 col-sm-4 border border-black">
                    <div class="row">
                        <div class="col m-3 p-2">
                            <table class="table table-border align-middle">
                                <thead>
                                    <th colspan="2">Blood Type</th>
                                    <th>Quantity</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2">A</td>
                                        <td>A+</td>
                                        <td><input class="form-control" type="number" name="aPositive" id="aPositive"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><input class="form-control" type="number" name="aNegative" id="aNegative"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">B</td>
                                        <td>B+</td>
                                        <td><input class="form-control" type="number" name="bPositive" id="bPositive"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><input class="form-control" type="number" name="bNegative" id="bNegative"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">O</td>
                                        <td>O+</td>
                                        <td><input class="form-control" type="number" name="oPositive" id="oPositive"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><input class="form-control" type="number" name="oNegative" id="oNegative"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">AB</td>
                                        <td>AB+</td>
                                        <td><input class="form-control" type="number" name="abPositive" id="abPositive"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><input class="form-control" type="number" name="abNegative" id="abNegative"
                                                min="0" max="10000" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            
                {{-- COL - 3 --}}
                <div class="col-4 col-sm-4 border border-black">
                    <div class="row">
                        <div class="col m-3 p-2">
                            <table class="table table-bordered border-black mx-auto table-hover">
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
                        </div>

                    </div>

                </div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection

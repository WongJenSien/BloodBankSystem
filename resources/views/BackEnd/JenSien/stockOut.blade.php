@extends('BackEnd.app')

@section('content')
    <div class="container">
        <form action="{{ url('remove-inventory') }}" method="POST">
            @csrf
            <div class="row">

                {{-- COL - 1 --}}
                <div class="col text-middle align-middle mx-auto">
                    <div class="form-group row">
                        <label for="ship-id" class="col-sm-4 col-form-label ">Shipment ID:</label>
                        <div class="col-sm-8">
                            <input type="text" name="ship_id" id="ship_id" class="form-control-plaintext"
                                value="{{ $shipmentID }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ship-today" class="col-sm-4 col-form-label">Request Date:</label>
                        <div class="col-sm-8">
                            <input type="date" name="ship_today" id="ship_today" class="form-control-plaintext"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ship-location" class="col-sm-4 col-form-label">Location:</label>
                        <div class="col-sm-8">
                            <select name="location" class="form-select" aria-label="Default select example">
                                <option selected>Select an Location</option>
                                @foreach ($hospitalList as $key => $value)
                                    <option value="{{$key}}">{{$value['Name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ship-date" class="col-sm-4 col-form-label">Shipment Date:</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="date" name="ship_date" id="ship_date"
                                onclick="getShipDate()" value="{{ date('Y-m-d') }}" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="ship-description"
                            class="col-sm-4 col-form-label">Description:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="description" name="description" rows="7" placeholder="Type here..." required></textarea>
                        </div>
                    </div>

                </div>

                {{-- COL - 2 --}}
                <div class="col-4 col-sm-4">
                    <div class="row">
                        <div class="col m-3 p-2">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <th colspan="2">Blood Type</th>
                                    <th>Quantity</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2">A</td>
                                        <td>A+</td>
                                        <td><input class="form-control" type="number" name="aPositive" id="aPositive"
                                                min="0" max="{{$max['bloodTypeA']['Available_P']}}" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><input class="form-control" type="number" name="aNegative" id="aNegative"
                                                min="0" max="{{$max['bloodTypeA']['Available_N']}}" value="0" onchange="countBlood()" /></td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">B</td>
                                        <td>B+</td>
                                        <td><input class="form-control" type="number" name="bPositive" id="bPositive"
                                                min="0"  max="{{$max['bloodTypeB']['Available_P']}}" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><input class="form-control" type="number" name="bNegative" id="bNegative"
                                                min="0" max="{{$max['bloodTypeB']['Available_N']}}" value="0" onchange="countBlood()" /></td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">O</td>
                                        <td>O+</td>
                                        <td><input class="form-control" type="number" name="oPositive" id="oPositive"
                                                min="0" max="{{$max['bloodTypeO']['Available_P']}}" value="0" onchange="countBlood()" /></td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><input class="form-control" type="number" name="oNegative" id="oNegative"
                                                min="0" max="{{$max['bloodTypeO']['Available_N']}}" value="0" onchange="countBlood()" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2">AB</td>
                                        <td>AB+</td>
                                        <td><input class="form-control" type="number" name="abPositive" id="abPositive"
                                                min="0" max="{{$max['bloodTypeAB']['Available_P']}}" value="0" onchange="countBlood()" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><input class="form-control" type="number" name="abNegative" id="abNegative"
                                                min="0" max="{{$max['bloodTypeAB']['Available_N']}}" value="0" onchange="countBlood()" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- COL - 3 --}}
                <div class="col-4 col-sm-4">
                    <div class="row">
                        <div class="col m-3 p-2">
                            <table class="table table-bordered mx-auto table-hover">
                                <thead>
                                    <tr><th colspan="2">Current Stock</th></tr>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>A+</td>
                                        <td><label>{{$max['bloodTypeA']['Available_P']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><label>{{$max['bloodTypeA']['Available_N']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>B+</td>
                                        <td><label>{{$max['bloodTypeB']['Available_P']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><label>{{$max['bloodTypeB']['Available_N']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>O+</td>
                                        <td><label>{{$max['bloodTypeO']['Available_P']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><label>{{$max['bloodTypeO']['Available_N']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>AB+</td>
                                        <td><label>{{$max['bloodTypeAB']['Available_P']}}</label></td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><label>{{$max['bloodTypeAB']['Available_N']}}</label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection

@extends('BackEnd.app')

@section('content')
    <div class="container">
        <form action="{{ url('add-inventory') }}" method="POST">
            @csrf
            <div class="container">
                <div class="row">
                    {{-- EventID COL --}}
                    <div class="col">
                        <div class="mb-3 row">
                            <label for="eventID" class="col-sm-2 col-form-label">Event ID: </label>
                            <div class="col-sm-10 text-start">
                                <select name="eventID" id="eventID" class="form-select" style="width: fit-content" data-width="fit"
                                    aria-label="Default select example">
                                    <option selected>Select an Event</option>
                                    @foreach ($eventInfo as $key)
                                        <option value="{{ $key['EventID'] }}">{{ $key['Name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- InventoryID COL --}}
                    <div class="col">
                        <div class="mb-3 row border border-black ">
                            <label for="inventoryID" class="col-sm-2 col-form-label border border-black"> ID: </label>
                            <div class="col-sm-10 text-start">
                                <input type="text" readonly class="form-control-plaintext fw-bold" name="inventoryID"
                                    id="inventoryID" value="{{ $newID }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col border border-black">
                    <div class="row">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <th colspan="2">Category</th>
                                <th>Quantity</th>
                                <th>Expired Date</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="2"><b>A</b></td>
                                    <td>A+</td>
                                    <td><input type="number" name="aPositive" id="aPositive" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_A_P" id="expiredDate_A_P"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>

                                </tr>
                                <tr>
                                    <td>A-</td>
                                    <td><input type="number" name="aNegative" id="aNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_A_N" id="expiredDate_A_N"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>B</b></td>
                                    <td>B+</td>
                                    <td><input type="number" name="bPositive" id="bPositive" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_B_P" id="expiredDate_B_P"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>B-</td>
                                    <td><input type="number" name="bNegative" id="bNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_B_N" id="expiredDate_B_N"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>O</b></td>
                                    <td>O+</td>
                                    <td><input type="number" name="oPositive" id="oPositive" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_O_P" id="expiredDate_O_P"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>O-</td>
                                    <td><input type="number" name="oNegative" id="oNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_O_N" id="expiredDate_O_N"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>AB</b></td>
                                    <td>AB+</td>
                                    <td><input type="number" name="abPositive" id="abPositive" min="0"
                                            max="10000" value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_AB_P" id="expiredDate_AB_P"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>AB-</td>
                                    <td><input type="number" name="abNegative" id="abNegative" min="0"
                                            max="10000" value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate_AB_N" id="expiredDate_AB_N"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col col-lg-2 border border-danger">
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
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection

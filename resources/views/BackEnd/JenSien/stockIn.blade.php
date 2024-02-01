@extends('BackEnd.app')

@section('content')
    <div class="container">
        <form action="{{ url('add-inventory') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col text-start">
                    <div class="col m-3">
                        <span class="m-3">Event ID: </span>
                        <select class="selectpicker form-select-sm p-2" data-width="fit" aria-label="Default select example">
                            <option selected>Select an Event</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="col text-end">
                    <div class="col m-3 align-middle">
                        <span class="m-3">ID: 123</span>
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
                                    <td><input type="date" name="expiredDate-A-P" id="expiredDate-A-P" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>

                                </tr>
                                <tr>
                                    <td>A-</td>
                                    <td><input type="number" name="aNegative" id="aNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-A-N" id="expiredDate-A-N" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>B</b></td>
                                    <td>B+</td>
                                    <td><input type="number" name="bPositive" id="bPositive" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-B-P" id="expiredDate-B-P" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>B-</td>
                                    <td><input type="number" name="bNegative" id="bNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-B-N" id="expiredDate-B-N" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>O</b></td>
                                    <td>O+</td>
                                    <td><input type="number" name="oPositive" id="oPositive" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-O-P" id="expiredDate-O-P" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>O-</td>
                                    <td><input type="number" name="oNegative" id="oNegative" min="0" max="10000"
                                            value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-O-N" id="expiredDate-O-N" onclick="dateRange()"
                                            value="{{ date('Y-m-d') }}" /></td>
                                </tr>

                                <tr>
                                    <td rowspan="2"><b>AB</b></td>
                                    <td>AB+</td>
                                    <td><input type="number" name="abPositive" id="abPositive" min="0"
                                            max="10000" value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-AB-P" id="expiredDate-AB-P"
                                            onclick="dateRange()" value="{{ date('Y-m-d') }}" /></td>
                                </tr>
                                <tr>
                                    <td>AB-</td>
                                    <td><input type="number" name="abNegative" id="abNegative" min="0"
                                            max="10000" value="0" onchange="countBlood()" /></td>
                                    <td><input type="date" name="expiredDate-AB-N" id="expiredDate-AB-N"
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


    {{-- <form action="{{url('add-inventory')}}" method="POST">
    @csrf
    <input type="text" name="name" id="name" class="form-control my-4 py-2"
        placeholder="Name">
    <input type="password" name="password" id="password" class="form-control my-4 py-2"
        placeholder="Password">
    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form> --}}
@endsection

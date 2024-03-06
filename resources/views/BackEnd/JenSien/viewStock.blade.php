@extends('BackEnd.app')

@section('content')
    {{-- DISPLAY SESSION AFTER STOCK-IN --}}
    {{-- 4-IMG-VIEW --}}
    <div class="container text-center mx-auto">
        <div class="row align-items-start">
            <div class="col px-md-3 m-1">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgA.png') }}" alt="BloodType-A" />
                    {{-- Total Number of Blood Type For A --}}
                    <h4 class="text-black">{{ $totalNumOfBlood['Blood_A'] }}</h4>
                </div>
            </div>
            <div class="col px-md-3 m-1">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgB.png') }}" alt="BloodType-B" />
                    {{-- Total Number of Blood Type For B --}}
                    <h4 class="text-black">{{ $totalNumOfBlood['Blood_B'] }}</h4>
                </div>
            </div>
            <div class="col px-md-3 m-1">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgO.png') }}" alt="BloodType-O" />
                    {{-- Total Number of Blood Type For O --}}
                    <h4 class="text-black">{{ $totalNumOfBlood['Blood_O'] }}</h4>
                </div>
            </div>
            <div class="col px-md-3 m-1">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgAB.png') }}" alt="BloodType-AB" />
                    {{-- Total Number of Blood Type For AB --}}
                    <h4 class="text-black">{{ $totalNumOfBlood['Blood_AB'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    {{-- TOP-SIDE-VIEW --}}
    <div class="container mx-auto mt-5">
        <div class="row text-start">
            <h2 class="text-black">Recent Activity</h2>
        </div>
        <div class="row">
            <div class="col border border-black ">
                <table class="table table-hover mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Blood Type</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Name</td>
                            <td>Date</td>
                            <td>Blood</td>
                            <td>Quantity</td>
                            <td>Action</td>
                        </tr>
                        {{-- @foreach ($last5Record as $key)
                            <tr>{{$key}}</tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- VIEW-MAIN-BODY --}}
    <div class="container text-center mx-auto border border-black mt-3">
        {{-- BLOOOD-TYPE-A-VIEW --}}
        <div class="row border border-danger mt-3">
            {{-- LEFT-SIDE --}}
            <div class="col-3 col-sm-3 border border-black ml-1">
                <div class="row">
                    <div class="container border border-danger">
                        {{-- COL-1 --}}
                        <div class="row border border-black p-1 m-1">
                            <img class="img-bloodType" src="{{ url('/Image/imgA.png') }}" alt="BloodType-A" />
                            <h3>Blood Type : A</h3>
                        </div>
                        {{-- COL-2 --}}
                        <div class="row border border-black p-1 m-1">
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>A+</td>
                                        <td><label for="BloodType-A-Positive"
                                                id="BloodType-A-Positive">{{ $numOfBlood['aPositive'] }}</label></td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><label for="BloodType-A-Negative"
                                                id="BloodType-A-Negative">{{ $numOfBlood['aNegative'] }}</label></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <td><label for="BloodType-A-Total"
                                                id="BloodType-A-Total">{{ $totalNumOfBlood['Blood_A'] }}</label></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- COL-3 --}}
                        <div class="row border border-black p-1 m-1">
                            <h5 class="text-start"><u>Summary</u></h5>
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle" rowspan="2">Available</td>
                                        <td>A+</td>
                                        <td><label for="BloodType-A-Positive-Available"
                                                id="BloodType-A-Positive-Available">{{ $status_info['bloodTypeA']['Available_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><label for="BloodType-A-Negative-Available"
                                                id="BloodType-A-Negative-Available">{{ $status_info['bloodTypeA']['Available_N'] }}</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="align-middle" rowspan="2">Shipment</td>
                                        <td>A+</td>
                                        <td><label for="BloodType-A-Positive-Shipment"
                                                id="BloodType-A-Positive-Shipment">{{ $status_info['bloodTypeA']['Shipment_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>A-</td>
                                        <td><label for="BloodType-A-Negative-Shipment"
                                                id="BloodType-A-Negative-Shipment">{{ $status_info['bloodTypeA']['Shipment_N'] }}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RIGHT-SIDE --}}
            <div class="col border border-black ml-2 ">
                <div class="row">
                    <div class="container inv-activity-container">
                        <div class="row inv-activity-fixed">
                            <table class="table table-border table-hover header-stick-top">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Blood Type</th>
                                        <th>Expired Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($info['bloodTypeA'] as $key => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $item['bloodType'] === 'aPositive' ? 'A+' : 'A-' }}</td>
                                            <td>{{ $item['expirationDate'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td><a href="{{ $key }}">View Details</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- END-OF-BLOOOD-TYPE-A-VIEW --}}

        {{-- BLOOOD-TYPE-B-VIEW --}}
        <div class="row border border-danger mt-3">
            {{-- LEFT-SIDE --}}
            <div class="col-3 col-sm-3 border border-black ml-1">
                <div class="row">
                    <div class="container border border-danger">
                        {{-- COL-1 --}}
                        <div class="row border border-black p-1 m-1">
                            <img class="img-bloodType" src="{{ url('/Image/imgB.png') }}" alt="BloodType-B" />
                            <h3>Blood Type : B</h3>
                        </div>
                        {{-- COL-2 --}}
                        <div class="row border border-black p-1 m-1">
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B+</td>
                                        <td><label for="BloodType-B-Positive"
                                                id="BloodType-B-Positive">{{ $numOfBlood['bPositive'] }}</label></td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><label for="BloodType-B-Negative"
                                                id="BloodType-B-Negative">{{ $numOfBlood['bNegative'] }}</label></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <td><label for="BloodType-B-Total"
                                                id="BloodType-B-Total">{{ $totalNumOfBlood['Blood_B'] }}</label></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- COL-3 --}}
                        <div class="row border border-black p-1 m-1">
                            <h5 class="text-start"><u>Summary</u></h5>
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle" rowspan="2">Available</td>
                                        <td>B+</td>
                                        <td><label for="BloodType-B-Positive-Available"
                                                id="BloodType-B-Positive-Available">{{ $status_info['bloodTypeB']['Available_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><label for="BloodType-B-Negative-Available"
                                                id="BloodType-B-Negative-Available">{{ $status_info['bloodTypeB']['Available_N'] }}</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="align-middle" rowspan="2">Shipment</td>
                                        <td>B+</td>
                                        <td><label for="BloodType-B-Positive-Shipment"
                                                id="BloodType-B-Positive-Shipment">{{ $status_info['bloodTypeB']['Shipment_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B-</td>
                                        <td><label for="BloodType-B-Negative-Shipment"
                                                id="BloodType-B-Negative-Shipment">{{ $status_info['bloodTypeB']['Shipment_N'] }}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RIGHT-SIDE --}}
            <div class="col border border-black ml-2 ">
                <div class="row">
                    <div class="container inv-activity-container">
                        <div class="row inv-activity-fixed">
                            <table class="table table-border table-hover header-stick-top">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Blood Type</th>
                                        <th>Expired Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($info['bloodTypeB'] as $key => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $item['bloodType'] === 'bPositive' ? 'B+' : 'B-' }}</td>
                                            <td>{{ $item['expirationDate'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td><a href="{{ $key }}">View Details</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- END-OF-BLOOOD-TYPE-B-VIEW --}}

        {{-- BLOOOD-TYPE-O-VIEW --}}
        <div class="row border border-danger mt-3">
            {{-- LEFT-SIDE --}}
            <div class="col-3 col-sm-3 border border-black ml-1">
                <div class="row">
                    <div class="container border border-danger">
                        {{-- COL-1 --}}
                        <div class="row border border-black p-1 m-1">
                            <img class="img-bloodType" src="{{ url('/Image/imgO.png') }}" alt="BloodType-O" />
                            <h3>Blood Type : O</h3>
                        </div>
                        {{-- COL-2 --}}
                        <div class="row border border-black p-1 m-1">
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>O+</td>
                                        <td><label for="BloodType-O-Positive"
                                                id="BloodType-O-Positive">{{ $numOfBlood['oPositive'] }}</label></td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><label for="BloodType-O-Negative"
                                                id="BloodType-O-Negative">{{ $numOfBlood['oNegative'] }}</label></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <td><label for="BloodType-O-Total"
                                                id="BloodType-O-Total">{{ $totalNumOfBlood['Blood_O'] }}</label></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- COL-3 --}}
                        <div class="row border border-black p-1 m-1">
                            <h5 class="text-start"><u>Summary</u></h5>
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle" rowspan="2">Available</td>
                                        <td>O+</td>
                                        <td><label for="BloodType-O-Positive-Available"
                                                id="BloodType-O-Positive-Available">{{ $status_info['bloodTypeO']['Available_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><label for="BloodType-O-Negative-Available"
                                                id="BloodType-O-Negative-Available">{{ $status_info['bloodTypeO']['Available_N'] }}</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="align-middle" rowspan="2">Shipment</td>
                                        <td>O+</td>
                                        <td><label for="BloodType-O-Positive-Shipment"
                                                id="BloodType-O-Positive-Shipment">{{ $status_info['bloodTypeO']['Shipment_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>O-</td>
                                        <td><label for="BloodType-O-Negative-Shipment"
                                                id="BloodType-O-Negative-Shipment">{{ $status_info['bloodTypeO']['Shipment_N'] }}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RIGHT-SIDE --}}
            <div class="col border border-black ml-2 ">
                <div class="row">
                    <div class="container inv-activity-container">
                        <div class="row inv-activity-fixed">
                            <table class="table table-border table-hover header-stick-top">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Blood Type</th>
                                        <th>Expired Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($info['bloodTypeO'] as $key => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $item['bloodType'] === 'oPositive' ? 'O+' : 'O-' }}</td>
                                            <td>{{ $item['expirationDate'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td><a href="{{ $key }}">View Details</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- END-OF-BLOOOD-TYPE-O-VIEW --}}

         {{-- BLOOOD-TYPE-AB-VIEW --}}
         <div class="row border border-danger mt-3">
            {{-- LEFT-SIDE --}}
            <div class="col-3 col-sm-3 border border-black ml-1">
                <div class="row">
                    <div class="container border border-danger">
                        {{-- COL-1 --}}
                        <div class="row border border-black p-1 m-1">
                            <img class="img-bloodType" src="{{ url('/Image/imgAB.png') }}" alt="BloodType-AB" />
                            <h3>Blood Type : AB</h3>
                        </div>
                        {{-- COL-2 --}}
                        <div class="row border border-black p-1 m-1">
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Blood Type</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>AB+</td>
                                        <td><label for="BloodType-AB-Positive"
                                                id="BloodType-AB-Positive">{{ $numOfBlood['abPositive'] }}</label></td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><label for="BloodType-AB-Negative"
                                                id="BloodType-AB-Negative">{{ $numOfBlood['abNegative'] }}</label></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <td><label for="BloodType-AB-Total"
                                                id="BloodType-AB-Total">{{ $totalNumOfBlood['Blood_AB'] }}</label></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- COL-3 --}}
                        <div class="row border border-black p-1 m-1">
                            <h5 class="text-start"><u>Summary</u></h5>
                            <table class="table table-border table-hover">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle" rowspan="2">Available</td>
                                        <td>AB+</td>
                                        <td><label for="BloodType-O-Positive-Available"
                                                id="BloodType-O-Positive-Available">{{ $status_info['bloodTypeAB']['Available_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><label for="BloodType-AB-Negative-Available"
                                                id="BloodType-AB-Negative-Available">{{ $status_info['bloodTypeAB']['Available_N'] }}</label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="align-middle" rowspan="2">Shipment</td>
                                        <td>AB+</td>
                                        <td><label for="BloodType-AB-Positive-Shipment"
                                                id="BloodType-AB-Positive-Shipment">{{ $status_info['bloodTypeAB']['Shipment_P'] }}</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>AB-</td>
                                        <td><label for="BloodType-AB-Negative-Shipment"
                                                id="BloodType-AB-Negative-Shipment">{{ $status_info['bloodTypeAB']['Shipment_N'] }}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RIGHT-SIDE --}}
            <div class="col border border-black ml-2 ">
                <div class="row">
                    <div class="container inv-activity-container">
                        <div class="row inv-activity-fixed">
                            <table class="table table-border table-hover header-stick-top">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Blood Type</th>
                                        <th>Expired Date</th>
                                        <th>Status</th>
                                        <th>View Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($info['bloodTypeAB'] as $key => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $item['bloodType'] === 'abPositive' ? 'AB+' : 'AB-' }}</td>
                                            <td>{{ $item['expirationDate'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td><a href="{{ $key }}">View Details</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        {{-- END-OF-BLOOOD-TYPE-AB-VIEW --}}
    </div>
@endsection

@extends('BackEnd.app')

@section('content')
    <style>
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
        }
    </style>

    <script>
        function showQr(){
            document.getElementById("qr").style.display = "block";
        }
    </script>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-5" style="background-color: pink;border-radius:33px;min-height:500px;">
            <div>&nbsp;</div>

            <div style="text-align: center;">
                <img src="{{ asset('storage/' . $user['path']) }}" class="profile-image" />
            </div>

            <div>&nbsp;</div>

            <table style="width: 100%;background-color:white;color:black;">
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>{{ $user['name'] }}</td>
                </tr>
                <tr>
                    <td>IC</td>
                    <td>:</td>
                    <td>{{ $user['identityCard'] }}</td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td>:</td>
                    <td>{{ $user['BOD'] }}</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>:</td>
                    <td>{{ $user['gender'] }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td>{{ $user['contactNumber'] }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $user['emailAddress'] }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td>{{ $user['address'] }}</td>
                </tr>
                <tr>
                    <td>Postcode</td>
                    <td>:</td>
                    <td>{{ $user['postcode'] }}</td>
                </tr>
            </table>

            <div>&nbsp;</div>

            <div style="text-align: center;">
                <a href="{{ route('editProfile') }}" class="btn btn-primary">Edit Info</a>
                @if (session('user.roleID') == 1)
                <a href="{{ route('addAdmin') }}" class="btn btn-primary">Add Admin</a>
                @endif
            </div>
        </div>
        @if (session('user.roleID') == 2)
            <div class="col-1">&nbsp;</div>
            <div class="col-5">
                <div class="row">
                    <div class="col-12" style="background-color: pink;min-height:150px;">
                        <h4 style="font-weight: bold;color:black;text-align:left;">Activity</h4>
                        <div style="border-radius:50px;background-color:white;min-height:100px;text-align:left;">
                            @if (!empty($activity))
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 80px;">
                                            <img src="{{ asset('storage/activity.png') }}" class="image-fluid"
                                                style="width:80px;margin-left:50px;" />
                                        </td>
                                        <td style="text-align: center;color:black;">
                                            <b>{{ $activity['eventName'] }}</b><br /><small>Venue:
                                                {{ $activity['eventVenue'] }}</small><br /><small>Date :
                                                {{ $activity['eventStartDate'] }} -
                                                {{ $activity['eventEndDate'] }}</small><br /><small>Time : Mon - Sun
                                                ({{ $activity['eventStartTime'] }} -
                                                {{ $activity['eventEndTime'] }})</small>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 80px;">
                                            <img src="{{ asset('storage/activity.png') }}" class="image-fluid"
                                                style="width:80px;margin-left:50px;" />
                                        </td>
                                        <td style="text-align: center;color:black;">
                                            <b>No Record</b>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                <div>&nbsp;</div>

                <div class="row">
                    <div class="col-12" style="background-color: pink;min-height:200px;">
                        <h4 style="font-weight: bold;color:black;text-align:left;">Rewards</h4>
                        <div style="border-radius:50px;background-color:white;min-height:100px;text-align:left;">
                            @if (!empty($reward))
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ asset('storage/reward.png') }}" class="image-fluid"
                                            style="width:100px;margin-left:50px;" />
                                    </td>
                                    <td style="text-align: center;color:black;">
                                        <b>{{ $reward['name'] }}</b><br />
                                        <b>Discount Coupons</b> : {{ $reward['code'] }}<br /><br />
                                        <a href="#" class="btn btn-primary" onclick="showQr();">Redeem</a><br /><br />

                                        <div id="qr" style="display: none;">
                                            {!! $reward['qr'] !!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @else
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ asset('storage/reward.png') }}" class="image-fluid"
                                            style="width:100px;margin-left:50px;" />
                                    </td>
                                    <td style="text-align: center;color:black;">
                                        <b>No Record</b>
                                    </td>
                                </tr>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>

                <div>&nbsp;</div>

                <div class="row">
                    <div class="col-12" style="background-color: pink;min-height:150px;">
                        <h4 style="font-weight: bold;color:black;text-align:left;">Blood Test Certification</h4>
                        <div style="border-radius:50px;background-color:white;min-height:100px;text-align:left;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ asset('storage/cert.png') }}" class="image-fluid"
                                            style="width:100px;margin-left:50px;" />
                                    </td>
                                    <td style="text-align: center;color:black;">
                                        <b>Blood Test Result</b><br /><br /><a href="view-certificate/{{session('user.key')}}"
                                            class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

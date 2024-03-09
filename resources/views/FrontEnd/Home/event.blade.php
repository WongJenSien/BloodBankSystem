@extends('FrontEnd.app')

@section('content')
    <h2 style="font-weight: bold;color:black;text-align:center;">Event</h2>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10">
            @if (!empty($paginationData))
                @foreach ($paginationData as $key => $value)
                    <div class="row">
                        <div class="col-12" style="background-color: pink;min-height:150px;">
                            <div>&nbsp;</div>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: center;width: 50%;">
                                        <img src="{{ asset('storage/' . $value['path']) }}" class="image-fluid"
                                            style="width:300px;" />
                                    </td>
                                    <td style="text-align: center;width: 50%;">
                                        <div style="color:black;background-color:white;">
                                            <b>{{ $value['eventName'] }}</b><br /><small>{{ $value['eventVenue'] }}</small><br /><small>Date
                                                : {{ $value['eventStartDate'] }} -
                                                {{ $value['eventEndDate'] }}</small><br /><small>Time : Mon - Sun
                                                ({{ $value['eventStartTime'] }} - {{ $value['eventEndTime'] }})
                                            </small>
                                        </div>

                                        @if (session('user.roleID') == 2)
                                            @if (!in_array($key, $attended))
                                                <br /><a href="{{ route('attend', ['key' => $key]) }}"
                                                    class="btn btn-success">Attend</a>
                                            @else
                                                <br /><a href="{{ route('cancel', ['key' => $key]) }}"
                                                    class="btn btn-danger">Cancel</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>&nbsp;</div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-12" style="background-color: pink;min-height:50px;">
                        <div>&nbsp;</div>
                        <h3 style="text-align:center;">No Record Found</h3>
                        <div>&nbsp;</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

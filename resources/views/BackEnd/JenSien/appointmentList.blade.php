@extends('BackEnd.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <span class="h3 text-dark text-decoration-underline text-center">
                    Appointment List
                </span>
            </div>
            <div class="col-2 align-self-end">
                <a class="btn btn-primary p-2 m-2" role="button" target="_blank" href="{{url('qrcode-scanner')}}">Scan</a>
            </div>
        </div>
        <div class="row">
            <div class="container">
                @if ($record != null)
                    <table class="table hover table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Appointment ID</th>
                                <th>Status</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($record as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['userID'] }}</td>
                                    <td>{{ $item['userName'] }}</td>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item['status'] }}</td>
                                    <td
                                        style="color: {{ $item['result']['testResult'] === 'Pass' ? 'green' : ($item['result']['testResult'] === 'Fail' ? 'red' : 'black') }}">
                                        {{ $item['result']['testResult'] }}
                                    </td>
                                    <td>
                                        <a data-toggle="modal" data-target="#insertResult{{ $key }}"
                                            style="text-decoration: underline; cursor:pointer; color:blue;">Result</a>
                                    </td>
                                </tr>
                                {{-- INSERT RESULT MODEL --}}
                                <div class="modal fade" id="insertResult{{ $key }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Result</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('insert-bloodtest-result') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="container">
                                                        <div class="row">
                                                            <span class="font-weight-bold text-start">Appointment ID: <span
                                                                    class="font-weight-normal">{{ $key }}</span></span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="font-weight-bold text-start">User Name : <span
                                                                    class="font-weight-normal">{{ $item['userName'] }}</span></span>
                                                        </div>
                                                        <div class="row">
                                                            <span class="font-weight-bold text-start">Date : <span
                                                                    class="font-weight-normal">{{ date('Y-m-d') }}</span></span>
                                                        </div>
                                                        <div class="row  m-3">
                                                            <div role="group{{ $key }}"
                                                                aria-label="Basic example">
                                                                <input type="hidden" name="appID"
                                                                    value="{{ $key }}" />
                                                                <div class="row">
                                                                    <div class="col text-end p-2">
                                                                        <label for="bloodType">Blood Type</label>
                                                                    </div>
                                                                    <div class="col text-start align-item-left p-2">
                                                                        <select name="bloodType" id="bloodType">
                                                                            @foreach (['a-Positive', 'a-Negative', 'b-Positive', 'b-Negative', 'o-Positive', 'o-Negative', 'ab-Positive', 'ab-Negative'] as $type)
                                                                                <option value="{{ $type }}"
                                                                                    {{ $item['result']['bloodType'] === $type ? 'selected' : '' }}>
                                                                                    {{ strtoupper(substr($type, 0, 1)) }}{{ substr($type, 1) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input type="radio" class="btn-check"
                                                                    name="result{{ $key }}"
                                                                    id="pass{{ $key }}" value="Pass"
                                                                    {{ $item['result']['testResult'] === 'Pass' ? 'checked' : '' }}>
                                                                <label class="btn btn-secondary shipment-status-model"
                                                                    for="pass{{ $key }}">PASS</label>

                                                                <input type="radio" class="btn-check"
                                                                    name="result{{ $key }}"
                                                                    id="fail{{ $key }}" value="Fail"
                                                                    {{ $item['result']['testResult'] === 'Fail' ? 'checked' : '' }}>
                                                                <label class="btn btn-secondary shipment-status-model"
                                                                    for="fail{{ $key }}">FAIL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="Submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- END --}}
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <span class="h4 text-danger m-3 p-3">
                        No record found.
                    </span>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('FrontEnd.app')

@section('content')
    <div class="container">
        <div class="row">
            @if ($record == null)
                {{-- NO RECORD FOUND --}}
                <h3>No result yet. [<a href="{{ url('make-appointment') }}">Click here</a>] to make an appointment</h3>
            @else
                @if ($status == 'Pending')
                    {{-- DISPLAY QR CODE --}}
                    <div class="container text-center">
                        <div class="row">
                            {{-- LEFT  SIDE --}}
                            <div class="col-2">
                                <div class="row">
                                    <div class="container border border-black rounded">
                                        <div class="row">
                                            <p class="h5 text-center text-dark text-decoration-underline p-2 m-2">
                                                Progress Bar
                                            </p>
                                        </div>
                                        {{-- Progress Bar Row --}}
                                        <div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" checked
                                                        disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-normal text-start">Select a
                                                        Hospital</span>
                                                </div>
                                            </div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" checked
                                                        disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-normal text-start">Make an
                                                        appointment</span>
                                                </div>
                                            </div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-bold text-start">Done</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- RIGHT SIDE --}}
                            <div class="col">
                                <div class="container">
                                    <div class="row">
                                        <div class="container w-50 h-50 d-inline-block">
                                            <div class="row">
                                                <div class="row">
                                                    <span class="text-dark text-decoration-underline font-weight-bold">
                                                        Appointment Information
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Location:
                                                        {{ $record['location'] }}</span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Date:
                                                        {{ $record['preferred_date'] }}</span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Time:
                                                        {{ $record['preferred_time'] }} </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col p-2 m-2 align-item-middle">
                                                        <img src="{{ url('/appQR/' . $record['fileName'] . '.jpg') }}"
                                                            alt="qrCode" class="border border-black"
                                                            style="min-width: 300px; min-height:300px;">
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="p-2 m-2 align-item-middle">
                                                        <a data-toggle="modal" data-target="#cancelAppointment"
                                                            role="button" class="btn btn-primary"
                                                            style="color: white">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="cancelAppointment" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Cancel
                                                                Appointment</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ url('cancel-appointment/' . $record['appID']) }}"
                                                            method="get">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <span
                                                                            class="text-dark h5 text-center">Information</span>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <span
                                                                                class="text-dark text-start font-weight-bold">Name:
                                                                            </span>
                                                                        </div>
                                                                        <div class="col">
                                                                            <span
                                                                                class="text-dark text-start font-weight-Normal">{{ $record['userName'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <span
                                                                                class="text-dark text-start font-weight-bold">IC:
                                                                            </span>
                                                                        </div>
                                                                        <div class="col">
                                                                            <span
                                                                                class="text-dark text-start font-weight-Normal">{{ $record['userIc'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <span
                                                                                class="text-dark text-start font-weight-bold">Location:
                                                                            </span>
                                                                        </div>
                                                                        <div class="col">
                                                                            <span
                                                                                class="text-dark text-start font-weight-Normal">{{ $record['location'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-3">
                                                                            <span
                                                                                class="text-dark text-start font-weight-bold">Appointment:
                                                                            </span>
                                                                        </div>
                                                                        <div class="col">
                                                                            <span
                                                                                class="text-dark text-start font-weight-Normal">{{ $record['preferred_date'] }}
                                                                                [{{ $record['preferred_time'] }}]</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col m-2">
                                                                            <span class="text-dark text-start p-2 m-3">
                                                                                <input
                                                                                    class="form-check-input border border-black"
                                                                                    type="checkbox" name="confirm"
                                                                                    id="confirm" required><span
                                                                                    class="m-1">I confirm want to
                                                                                    cancel the appointment</span>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="Submit"
                                                                    class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <img src="{{ url('/appQR/qr_code_65ebfc36eb408.jpg') }}" alt="qrCode"
                                                class="border border-black p-3 m-3" style="min-width: 300px; min-height:300px;"> --}}
                                            {{-- <div id="qrcode" style="width: 300px; height:300px;"></div>
                                            <script type="text/javascript">
                                                new QRCode(document.getElementById("qrcode"), "http://jindo.dev.naver.com/collie");
                                            </script> --}}
                                            {{-- <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
                                            <div id="qr"></div> --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($status == 'Done')
                    {{-- DISPLAY PDF --}}
                    <div class="container text-center">
                        <div class="row">
                            {{-- LEFT  SIDE --}}
                            <div class="col-2">
                                <div class="row">
                                    <div class="container border border-black rounded">
                                        <div class="row">
                                            <p class="h5 text-center text-dark text-decoration-underline p-2 m-2">
                                                Progress Bar
                                            </p>
                                        </div>
                                        {{-- Progress Bar Row --}}
                                        <div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" checked
                                                        disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-normal text-start">Select a
                                                        Hospital</span>
                                                </div>
                                            </div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" checked
                                                        disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-normal text-start">Make an
                                                        appointment</span>
                                                </div>
                                            </div>
                                            <div class="row align-middle text-start justify-content-center">
                                                <div class="col-2 p-2 text-end">
                                                    <input
                                                        class="form-check-input h5 border border-black justify-content-center"
                                                        type="checkbox" value="" id="flexCheckDisabled" checked
                                                        disabled>
                                                </div>
                                                <div class="col p-2">
                                                    <span class="text-dark font-weight-bold text-start">Done</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- RIGHT SIDE --}}
                            <div class="col">
                                <div class="container">
                                    <div class="row">
                                        <div class="container w-50 h-50 d-inline-block">
                                            <div class="row"><img src="{{ url('/Image/pdf.png') }}" alt="pdf"
                                                    class="p-3 m-3" style="min-width: 300px; min-height:300px;"></div>
                                            <div class="row">
                                                <a href="{{ url('download-result') }}" class="btn btn-primary btn-lg"
                                                    role="button">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
@endsection

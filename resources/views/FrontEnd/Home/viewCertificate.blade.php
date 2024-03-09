@extends('FrontEnd.app')

@section('content')
    <div class="container">
        <div class="row">
            @if ($record == null)
                <h3>No result yet. [<a href="{{ url('make-appointment') }}">Click here</a>] to make an appointment</h3>
            @else
                @if ($status == 'Pending')
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
                                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
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
                                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
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
                                        <div class="container border border-black w-50 h-50 d-inline-block">
                                            <div class="row">
                                                <div class="row">
                                                    <span class="text-dark text-decoration-underline font-weight-bold">
                                                        Appointment Information
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Location:</span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Date:</span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-dark text-start p-2 m-1">Time:</span>
                                                </div>
                                                    
                                            </div>
                                            <img src="{{ url('/appQR/qr_code_65ebfc36eb408.jpg') }}" alt="qrCode"
                                                class="border border-black p-3 m-3" style="min-width: 300px; min-height:300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
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
                                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
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
                                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
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
                                            <div class="row"><img src="{{ url('/Image/pdf.png') }}" alt="pdf"
                                                    class="p-3 m-3" style="min-width: 300px; min-height:300px;"></div>
                                            <div class="row">
                                                <a href="{{url('download-result')}}" class="btn btn-primary btn-lg" role="button">Download</a>
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

@extends('FrontEnd.app')

@section('content')
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
                                    <input class="form-check-input h5 border border-black justify-content-center"
                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
                                </div>
                                <div class="col p-2">
                                    <span class="text-dark font-weight-bold text-start">Select a Hospital</span>
                                </div>
                            </div>
                            <div class="row align-middle text-start justify-content-center">
                                <div class="col-2 p-2 text-end">
                                    <input class="form-check-input h5 border border-black justify-content-center"
                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
                                </div>
                                <div class="col p-2">
                                    <span class="text-dark font-weight-normal text-start">Make an appointment</span>
                                </div>
                            </div>
                            <div class="row align-middle text-start justify-content-center">
                                <div class="col-2 p-2 text-end">
                                    <input class="form-check-input h5 border border-black justify-content-center"
                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
                                </div>
                                <div class="col p-2">
                                    <span class="text-dark font-weight-normal text-start">Done</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RIGHT SIDE --}}
            <div class="col">
                <div class="row p-2 m-2">
                    <span class="h4 text-dark text-start font-weight-bold text-decoration-underline">
                        Step 1: Select an Hospital
                    </span>
                </div>
                {{-- record --}}
                @foreach ($data as $key => $item)
                    <div class="row border border-danger rounded m-3" style="background-color: rgba(252,186,186,0.5)">
                        <div class="container">
                            <div class="row ">
                                <div class="col-3 d-flex align-items-center justify-content-center">
                                    <img src="{{ url('/Image/' . $key . '.jpg') }}" alt="icon"
                                        style="max-width: 100%; height: auto;" />
                                </div>
                                <div class="col text-start">
                                    <div class="row m-2">
                                        <span class="h4 text-dark text-decoration-underline font-weight-bold text-start">
                                            {{ $item['Name'] }}
                                        </span>
                                    </div>
                                    <div class="row m-2 ">
                                        <div class="row p-2 m-1">
                                            <div class="col-1 text-center align-middle">
                                                <i class="material-icons">place</i>
                                            </div>
                                            <div class="col">
                                                <span class="m-2 text-start text-dark">{{ $item['Address'] }} </span>
                                            </div>
                                        </div>
                                        <div class="row p-2 m-1">
                                            <div class="col-1 text-center align-middle">
                                                <i class="material-icons">call</i>
                                            </div>
                                            <div class="col">
                                                <span class="m-2 text-start text-dark">{{ $item['Tel'] }} </span>
                                            </div>
                                        </div>
                                        <div class="row p-2 m-1">
                                            <div class="col-5">
                                                <a class="btn btn-secondary btn-lg"
                                                    href="{{ url('appointment-selected-hospital/' . $key) }}"
                                                    role="button">Make
                                                    an Appointment </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

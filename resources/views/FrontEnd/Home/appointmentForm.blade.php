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
                                        type="checkbox" value="" id="flexCheckDisabled" checked disabled>
                                </div>
                                <div class="col p-2">
                                    <span class="text-dark font-weight-normal text-start">Select a Hospital</span>
                                </div>
                            </div>
                            <div class="row align-middle text-start justify-content-center">
                                <div class="col-2 p-2 text-end">
                                    <input class="form-check-input h5 border border-black justify-content-center"
                                        type="checkbox" value="" id="flexCheckDisabled" disabled>
                                </div>
                                <div class="col p-2">
                                    <span class="text-dark font-weight-bold text-start">Make an appointment</span>
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
                        Step 2: Make an Appointment
                    </span>
                </div>
                <div class="row">
                    <form action="{{ url('store-appointment') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Location</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <span class="text-dark text-start font-weight-bold">{{ $location }}</span> <a
                                    href="{{ url('make-appointment') }}"><i class="material-icons">edit</i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Preferred Date</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <input type="date" name="preferred_date" id="preferred_date" onclick="dateRange()"
                                    value="{{ date('Y-m-d') }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Preferred Time</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <input type="time" name="preferred_time" id="preferred_time" min="08:00"
                                    max="22:00" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="font-weight-bold p-3 m-2 d-flex">
                                <span class="h4 text-start text-dark text-decoration-underline">Personal Information</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Name:</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <input type="text" name="user_name" id="user_name" value="{{ $user['name'] }}"
                                    disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Identity Card:</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <input type="text" name="user_age" id="user_age" value="{{ $user['identityCard'] }}"
                                    disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 text-end font-weight-bold d-flex align-items-center justify-content-center">
                                <span class="text-dark">Gender:</span>
                            </div>
                            <div class="col text-start p-2 m-2">
                                <input type="text" name="user_gender" id="user_gender" value="{{ $user['gender'] }}"
                                    disabled />
                            </div>
                        </div>
                        <input type="hidden" name='hospitalID' id="hospitalID" value="{{ $hospitalID }}" />
                        
                        <div class="row p-2 m-1">
                            <div class="col-5">
                                <button class="btn btn-primary" type="submit">
                                    Make Appointment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Get the input element
        var timeInput = document.getElementById('preferred_time');

        // Add event listener to the input element
        timeInput.addEventListener('input', function() {
            // Get the selected time value
            var selectedTime = timeInput.value;

            // Get the current hour and minute
            var currentHour = parseInt(selectedTime.split(':')[0]);
            var currentMinute = parseInt(selectedTime.split(':')[1]);

            // Disable times outside the range 8:00 AM to 10:00 PM
            if (currentHour < 8 || currentHour >= 22 || (currentHour === 22 && currentMinute > 0)) {
                timeInput.value = ''; // Clear the input if time is outside range
            }
        });
    </script>
@endsection

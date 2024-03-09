@extends('BackEnd.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 m-auto">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <svg class="mx-auto my-3" xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                            fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>

                        <h2>Register</h2>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <input type="email" name="emailAddress" id="" class="form-control my-4 py-2"
                                placeholder="Email" required>
                            <input type="password" name="password" id="" class="form-control my-4 py-2"
                                placeholder="Password" required>
                            <input type="text" name="name" id="" class="form-control my-4 py-2"
                                placeholder="Name" required>
                            <input type="text" name="identityCard" id="" class="form-control my-4 py-2"
                                placeholder="Identity Card" required>
                            <input type="date" name="BOD" id="" class="form-control my-4 py-2"
                                placeholder="Birth Date" required>
                            <select name="gender" class="form-control my-4 py-2" required>
                                @foreach ($gender as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="contactNumber" id="" class="form-control my-4 py-2"
                                placeholder="Contact Number" minlength="10" maxlength="11" required>
                            <input type="text" name="address" id="" class="form-control my-4 py-2"
                                placeholder="Address" required>
                            <input type="text" name="postcode" id="" class="form-control my-4 py-2"
                                placeholder="Postcode" required>

                            <div class="text-center mt-3">
                                <button class="btn btn-primary">Register</button>
                            </div>
                        </form>

                        <div>&nbsp;</div>
                        <a href="{{ route('loginForm') }}">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

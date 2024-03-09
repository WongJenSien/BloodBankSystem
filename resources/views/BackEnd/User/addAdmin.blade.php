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

        .clickable {
            cursor: pointer;
        }
    </style>

    <script>
        function displaySelectedFile(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit-picture').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function triggerButton(id) {
            document.getElementById('edit-picture-button').click();
        }
    </script>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10" style="background-color: pink;border-radius:33px;min-height:600px;">
            <form action="{{ route('addAdmin') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>&nbsp;</div>

                <div style="text-align: center;">
                    <img src="{{ asset('storage/profile/default.png') }}" class="profile-image clickable"
                        onclick="triggerButton('edit-picture-button')" id="edit-picture" />

                    <div>&nbsp;</div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                            onclick="triggerButton('edit-picture-button')">Update</button>
                    </div>

                    <input type="file" id="edit-picture-button" name="photo"
                        onchange="displaySelectedFile(this,'edit-picture')" hidden accept="image/*">
                </div>

                <div>&nbsp;</div>

                <table style="width: 100%;background-color:white;color:black;">
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td><input type="email" name="emailAddress" id="" class="form-control"
                                placeholder="Email" required></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>:</td>
                        <td><input type="password" name="password" id="" class="form-control"
                                placeholder="Password" required></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>:</td>
                        <td><input type="text" name="name" id="" class="form-control" placeholder="Name"
                                required></td>
                    </tr>
                    <tr>
                        <td>IC</td>
                        <td>:</td>
                        <td><input type="text" name="identityCard" id="" class="form-control" placeholder="IC"
                                required></td>
                    </tr>
                    <tr>
                        <td>Birth Date</td>
                        <td>:</td>
                        <td><input type="date" name="BOD" id="" class="form-control"
                                placeholder="Birth Date" required></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>:</td>
                        <td>
                            <select name="gender" class="form-control" required>
                                @foreach ($gender as $key => $value)
                                    <option value="{{ $key }}">
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>:</td>
                        <td><input type="text" name="contactNumber" id="" class="form-control"
                                placeholder="Phone" minlength="10" maxlength="11" required></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td><input type="text" name="address" id="" class="form-control" placeholder="Address"
                                required></td>
                    </tr>
                    <tr>
                        <td>Postcode</td>
                        <td>:</td>
                        <td><input type="text" name="postcode" id="" class="form-control" placeholder="Postcode"
                                required></td>
                    </tr>
                </table>

                <div>&nbsp;</div>

                <div style="text-align: center;">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <div class="col-1">&nbsp;</div>
    </div>
@endsection

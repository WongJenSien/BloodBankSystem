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
            <form action="{{ route('updateEvent') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="key" value="{{ $record['key'] }}">
                <div>&nbsp;</div>

                <div style="text-align: center;">
                    <img src="{{ asset('storage/'.$record['path']) }}" class="profile-image clickable"
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
                        <td>Event Name</td>
                        <td>:</td>
                        <td><input type="text" name="eventName" id="" class="form-control"
                                placeholder="Event Name" value="{{ $record['eventName'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Sponsor</td>
                        <td>:</td>
                        <td><input type="text" name="eventSponsor" id="" class="form-control"
                                placeholder="Sponsor" value="{{ $record['eventSponsor'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Event Venue</td>
                        <td>:</td>
                        <td><input type="text" name="eventVenue" id="" class="form-control"
                                placeholder="Event Venue" value="{{ $record['eventVenue'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Event Start Date</td>
                        <td>:</td>
                        <td><input type="date" name="eventStartDate" id="" class="form-control"
                                placeholder="Event Start Date" value="{{ $record['eventStartDate'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Event End Date</td>
                        <td>:</td>
                        <td><input type="date" name="eventEndDate" id="" class="form-control"
                                placeholder="Event End Date" value="{{ $record['eventEndDate'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Event Start Time</td>
                        <td>:</td>
                        <td><input type="time" name="eventStartTime" id="" class="form-control"
                                placeholder="Event Start Time" value="{{ $record['eventStartTime'] }}" required></td>
                    </tr>
                    <tr>
                        <td>Event End Time</td>
                        <td>:</td>
                        <td><input type="time" name="eventEndTime" id="" class="form-control"
                                placeholder="Event End Time" value="{{ $record['eventEndTime'] }}" required></td>
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

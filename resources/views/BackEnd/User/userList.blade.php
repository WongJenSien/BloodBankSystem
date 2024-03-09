@extends('BackEnd.app')

@section('content')
    <h2 style="color: black;font-weight:bold;">User List</h2>

    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10" style="background-color: pink;border-radius:33px;min-height:600px;">
            <div>&nbsp;</div>
            <table class="table table-bordered" style="color:black;">
                <tr>
                    <th>Name</th>
                    <th>IC</th>
                    <th style="width: 450px;">Action</th>
                </tr>
                @foreach ($paginationData as $key => $value)
                <tr>
                    <td>{{ $value['name'] }}</td>
                    <td>{{ $value['identityCard'] }}</td>
                    <td>
                        <a href="{{ route('updateUserForm', ['key' => $key]) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('viewUserForm', ['key' => $key]) }}" class="btn btn-warning">View</a>
                        <a href="{{ route('deleteUser', ['key' => $key]) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                        <a href="{{ route('addRewardForm', ['key' => $key]) }}" class="btn btn-success">Add Reward</a>
                        <a href="{{ route('removeReward', ['key' => $key]) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove?')">Remove Reward</a>
                        <a href="#" class="btn btn-primary">Add Certificate</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="col-1">&nbsp;</div>
    </div>

    {{ $paginationData->links() }}
@endsection

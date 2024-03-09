@extends('BackEnd.app')

@section('content')
    <div class="row">
        <div class="col-1">&nbsp;</div>
        <div class="col-10" style="background-color: pink;border-radius:33px;min-height:200px;">
            <div>&nbsp;</div>
            <form action="{{ route('addReward', ['key' => $key]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <table style="width: 100%;background-color:white;color:black;">
                    <tr>
                        <td>Reward Name</td>
                        <td>:</td>
                        <td><input type="text" name="name" id="" class="form-control"
                                placeholder="Reward Name" required></td>
                    </tr>
                    <tr>
                        <td>Reward Code</td>
                        <td>:</td>
                        <td><input type="text" name="code" id="" class="form-control"
                                placeholder="Reward Code" required></td>
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

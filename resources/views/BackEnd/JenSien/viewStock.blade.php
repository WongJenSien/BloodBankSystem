@extends('BackEnd.app')

@section('content')
    {{-- @if (session('status'))
<h4>{{session('status')}}</h4>
@endif --}}

    <div class="container text-center mx-auto">
        <div class="row align-items-start">
            <div class="col px-md-5">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgA.png') }}" alt="BloodType-A" />
                    <h4 class="text-black">1000</h4>
                </div>
            </div>
            <div class="col px-md-5">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgB.png') }}" alt="BloodType-B" />
                    <h4 class="text-black">1000</h4>
                </div>
            </div>
            <div class="col px-md-5">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgO.png') }}" alt="BloodType-O" />
                    <h4 class="text-black">1000</h4>
                </div>
            </div>
            <div class="col px-md-5">
                <div class="col border border-dark cat-blood-color">
                    <img class="img-bloodType" src="{{ url('/Image/imgAB.png') }}" alt="BloodType-AB" />
                    <h4 class="text-black">1000</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto mt-5">
        <div class="row text-start">
            <h2 class="text-black">Recent Activity</h2>
        </div>
        <div class="row">
            <div class="col border border-black ">
                <table class="table table-hover mt-2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Blood Type</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No</td>
                            <td>Name</td>
                            <td>Date</td>
                            <td>Blood</td>
                            <td>Quantity</td>
                            <td>Action</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>







    {{-- <table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Password</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reference as $key => $item)
        <tr>
            <td>
                {{ $item['name'] }}
            </td>
            <td>
                {{ $item['password'] }}
            </td>
        </tr>
        @empty
        <tr>
            <td>No record</td>
        </tr>
        @endforelse
    </tbody>
</table> --}}
@endsection

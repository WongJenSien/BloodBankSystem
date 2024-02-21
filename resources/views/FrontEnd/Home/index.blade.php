@extends('FrontEnd.app')

@section('content')


    <h1>Index.blade.php</h1>
    <p>{{print_r($date)}}</p>
    {{-- <h5>return view('FrontEnd.Home.index')->with('inventoryID', $inventoryID)->with('expirationDate',$expirationDate)->with('quantity',$quantity)->with('status', $status);</h5>

    <h5>inventoryID: {{ $inventoryID }}</h5>
    <h5>Expiread Date</h5>
    @foreach ($expirationDate as $key=>$item)
    <p>{{ $key}} - {{ $item }}</p>
    @endforeach

    <h5>Quantity</h5>
    @foreach ($quantity as $key=>$item)
    <p>{{ $key}} - {{ $item }}</p>
    @endforeach

    <h5>Status</h5>
    <p>{{$status}}</p> 
    --}}
@endsection
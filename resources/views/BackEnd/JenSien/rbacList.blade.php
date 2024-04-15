@extends('BackEnd.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="3">No</th>
                            <th rowspan="3">User ID</th>
                            <th rowspan="3">User Name</th>
                            <th colspan="9">Permission</th>
                            <th rowspan="3">Action</th>
                        </tr>
                        <tr>
                            <td colspan="4" class="custom-inventory font-weight-bold">Inventory</td>
                            <td colspan="2" class="custom-shipment font-weight-bold">Shipment</td>
                            <td colspan="3" class="custom-event font-weight-bold">Event</td>
                        </tr>
                        <tr>
                            <td class="custom-inventory font-weight-bold">View</td>
                            <td class="custom-inventory font-weight-bold">Stock-In</td>
                            <td class="custom-inventory font-weight-bold">Stock-Edit</td>
                            <td class="custom-inventory font-weight-bold">Stock-Out</td>

                            <td class="custom-shipment font-weight-bold">View</td>
                            <td class="custom-shipment font-weight-bold">Update</td>

                            <td class="custom-event font-weight-bold">Add</td>
                            <td class="custom-event font-weight-bold">Edit</td>
                            <td class="custom-event font-weight-bold">Delete</td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($userList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['name'] }}</td>

                                <td
                                    class="custom-inventory {{ $item['permission']['inventoryControl']['read'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['inventoryControl']['read'] }}</td>
                                <td
                                    class="custom-inventory {{ $item['permission']['inventoryControl']['stockIn'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['inventoryControl']['stockIn'] }}</td>
                                <td
                                    class="custom-inventory {{ $item['permission']['inventoryControl']['stockEdit'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['inventoryControl']['stockEdit'] }}</td>
                                <td
                                    class="custom-inventory {{ $item['permission']['inventoryControl']['stockOut'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['inventoryControl']['stockOut'] }}</td>

                                <td
                                    class="custom-shipment {{ $item['permission']['shipmentControl']['view_shipment'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['shipmentControl']['view_shipment'] }}</td>
                                <td
                                    class="custom-shipment {{ $item['permission']['shipmentControl']['update_shipment'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['shipmentControl']['update_shipment'] }}</td>

                                <td
                                    class="custom-event {{ $item['permission']['eventControl']['add_event'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['eventControl']['add_event'] }}</td>
                                <td
                                    class="custom-event {{ $item['permission']['eventControl']['edit_event'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['eventControl']['edit_event'] }}</td>
                                <td
                                    class="custom-event {{ $item['permission']['eventControl']['delete_event'] == 'on' ? 'permission-on' : '' }}">
                                    {{ $item['permission']['eventControl']['delete_event'] }}</td>

                                <td>
                                    <a data-toggle="modal" data-target="#editPermission{{ $key }}"
                                        style="text-decoration: underline; cursor:pointer; color:blue;">Edit</a>
                                </td>

                            </tr>
                            {{-- MODEL --}}
                            <div class="modal fade" id="editPermission{{ $key }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit Result</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ url('edit-permission') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="container">

                                                    <div class="row">
                                                        <div class="col-md-auto">
                                                            <span class="h6 text-dark font-weight-bold">
                                                                User ID :
                                                            </span>
                                                        </div>
                                                        <div class="col text-start">
                                                            <span class="h6 text-dark font-weight-normal">
                                                                {{ $key }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-auto">
                                                            <span class="h6 text-dark font-weight-bold">
                                                                User Name :
                                                            </span>
                                                        </div>
                                                        <div class="col text-start">
                                                            <span class="h6 text-dark font-weight-normal">
                                                                {{ $item['name'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row  m-3">
                                                        <h5 class="text-start p-2">Permission List</h5>
                                                    </div>
                                                    <div class="row p-2 m-2">
                                                        {{-- Inventory Control --}}
                                                        <div class="container">
                                                            <div class="row">
                                                                <span class="text-dark font-weight-bold p-2 fs-2">
                                                                    Inventory
                                                                </span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="container">
                                                                    <div class="row text-start align-item-left">
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input inventory-checkbox"
                                                                                type="checkbox"
                                                                                name="read_inventory"
                                                                                id="read_inventory{{ $key }}"
                                                                                {{ $item['permission']['inventoryControl']['read'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="read_inventory{{ $key }}">View
                                                                                Inventory</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input inventory-checkbox"
                                                                                type="checkbox"
                                                                                name="stockIn_inventory"
                                                                                id="stockIn_inventory{{ $key }}"
                                                                                {{ $item['permission']['inventoryControl']['stockIn'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="stockIn_inventory{{ $key }}">Stock
                                                                                In</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input inventory-checkbox"
                                                                                type="checkbox"
                                                                                name="stockEdit_inventory"
                                                                                id="stockEdit_inventory{{ $key }}"
                                                                                {{ $item['permission']['inventoryControl']['stockEdit'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="stockEdit_inventory{{ $key }}">Stock
                                                                                Edit</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input inventory-checkbox"
                                                                                type="checkbox"
                                                                                name="stockOut_inventory"
                                                                                id="stockOut_inventory{{ $key }}"
                                                                                {{ $item['permission']['inventoryControl']['stockOut'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="stockOut_inventory{{ $key }}">Stock
                                                                                Out</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row p-2 m-2">
                                                        {{-- Shipment Control --}}
                                                        <div class="container">
                                                            <div class="row">
                                                                <span class="text-dark font-weight-bold p-2 fs-2">
                                                                    Shipment
                                                                </span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="container">
                                                                    <div class="row text-start align-item-left">
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input shipment-checkbox"
                                                                                type="checkbox" name="view_shipment"
                                                                                id="view_shipment{{ $key }}"
                                                                                {{ $item['permission']['shipmentControl']['view_shipment'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="view_shipment{{ $key }}">View
                                                                                Shipment</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input
                                                                                class="form-check-input shipment-checkbox"
                                                                                type="checkbox" name="update_shipment"
                                                                                id="update_shipment{{ $key }}"
                                                                                {{ $item['permission']['shipmentControl']['update_shipment'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="update_shipment{{ $key }}">Update
                                                                                Shipment</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row p-2 m-2">
                                                        {{-- Event Control --}}
                                                        <div class="container">
                                                            <div class="row">
                                                                <span class="text-dark font-weight-bold p-2 fs-2">
                                                                    Event
                                                                </span>
                                                            </div>
                                                            <div class="row">
                                                                <div class="container">
                                                                    <div class="row text-start align-item-left">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input event-checkbox"
                                                                                type="checkbox" name="add_event"
                                                                                id="add_event{{ $key }}"
                                                                                {{ $item['permission']['eventControl']['add_event'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="add_event{{ $key }}">Add
                                                                                Event</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input event-checkbox"
                                                                                type="checkbox" name="edit_event"
                                                                                id="edit_event{{ $key }}"
                                                                                {{ $item['permission']['eventControl']['edit_event'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="edit_event{{ $key }}">Edit
                                                                                Event</label>
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input event-checkbox"
                                                                                type="checkbox" name="delete_event"
                                                                                id="delete_event{{ $key }}"
                                                                                {{ $item['permission']['eventControl']['delete_event'] == 'on' ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="delete_event{{ $key }}">Delete
                                                                                Event</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="userKey" id="userKey"
                                                value="{{ $key }}">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="Submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- END --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- NAV BAR --}}
<nav class="navbar navbar-expand-lg bg-body-tertiary custom-nav">
    <div class="container-fluid custom-nav">
        <a class="navbar-brand navTitle" href="{{ route('landing') }}"> <img src="{{ url('/Image/Icon.png') }}"
                alt="icon" /> Blood
            Bank System </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>
            <form class="d-flex" id="searchForm" method="GET" action="{{ url('/search') }}">
                @csrf
                <input type="hidden" name="currentUrl" id="currentUrl">
                <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <script>
                // Wait for the DOM to be fully loaded
                document.addEventListener('DOMContentLoaded', function() {
                    // Get the current URL
                    var currentUrl = window.location.href;

                    // Set the value of the hidden input field to the current URL
                    document.getElementById('currentUrl').value = currentUrl;
                });
            </script>
        </div>
    </div>
</nav>





{{-- SIDE BAR --}}
@if (!session()->has('user'))
    <a href="{{ route('loginForm') }}" style="display: inline-block;color:black;font-weight:bold;">Click Here</a>
@endif
<div class="wrapper">
    <aside id="sidebar">
        <div class="d-flex">
            <button id="toggle-btn" type="button">
                <i class="lni lni-grid-alt"></i>
            </button>
            <div class="sidebar-logo">
                <a href="{{ route('profileForm') }}">
                    {{ session('user.name') }}</a>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('profileForm') }}" class="sidebar-link">
                    <i class="lni lni-user"></i>
                    <span>Profile</span>
                </a>
            </li>

            {{-- EventManagement --}}
            <li class="sidebar-item">
                <a href="{{ route('eventList') }}" class="sidebar-link">
                    <i class="lni lni-clipboard"></i>
                    <span>Event List</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('userList') }}" class="sidebar-link">
                    <i class="lni lni-users"></i>
                    <span>User List</span>
                </a>
            </li>


            <li class="sidebar-item">
                <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                    data-bs-target="#inventory" aria-expanded="false" aria-controls="inventory">
                    <i class="lni lni-package"></i>
                    <span>Inventory</span>
                </a>
                <ul id="inventory" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item ">
                        <a href="{{ url('add-inventory') }}" class="sidebar-link">
                            Stock In</a>
                        <a href="{{ url('remove-inventory') }}" class="sidebar-link">
                            Stock Out</a>
                        <a href="{{ url('view-inventory') }}" class="sidebar-link">
                            Stock Monitoring</a>
                    </li>
                </ul>
            </li>



            <li class="sidebar-item">
                <a href="{{ url('view-shipment') }}" class="sidebar-link">
                    <i class="lni lni-delivery"></i>
                    <span>Shipment</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ url('role-base-control') }}" class="sidebar-link">
                    <i class="lni lni-shield"></i>
                    <span>RBAC</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ url('appointment-list') }}" class="sidebar-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Appointment</span>
                </a>
            </li>

            {{-- REPORT --}}
            <li class="sidebar-item">
                <a href="{{ url('inventory-report') }}" class="sidebar-link">
                    <i class="bi bi-file-zip"></i>
                    <span>Report</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="sidebar-link"> <i
                    class="lni lni-exit"></i><span>Logout</span></a>
        </div>
    </aside>




    {{-- <div class="main p-3">
        <div class="text-center">
            <h1>Sidebar</h1>
        </div>
    </div>

</div> --}}

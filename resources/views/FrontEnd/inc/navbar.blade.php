@if (session()->has('user'))
    <div style="text-align: right;font-weight:bold;">
        <a href="{{ route('profileForm') }}" style="display: inline-block;color:black;font-weight:bold;">
            <h5>{{ session('user.name') }}</h5>
        </a>
        <span style="display: inline-block;">|</span>
        <a href="{{ route('logout') }}" style="display: inline-block;color:black;font-weight:bold;">
            <h5>Logout</h5>
        </a>
        &nbsp;&nbsp;&nbsp;
    </div>
@else
    <div style="text-align: right;font-weight:bold;">
        <a href="{{ route('loginForm') }}" style="display: inline-block;color:black;font-weight:bold;">
            <h5>Login</h5>
        </a>
        <span style="display: inline-block;">|</span>
        <a href="{{ route('registerForm') }}" style="display: inline-block;color:black;font-weight:bold;">
            <h5>Register</h5>
        </a>
        &nbsp;&nbsp;&nbsp;
    </div>
@endif


<nav class="navbar navbar-expand-lg bg-body-tertiary ">
    <div class="container-fluid custom-nav">
        <a class="navbar-brand navTitle" href="{{ route('landing') }}"> <img src="{{ url('/Image/Icon.png') }}"
                alt="icon" /> Blood Bank System </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active navSubTitle" aria-current="page" href="{{ route('landing') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link navSubTitle" href="{{ route('event') }}">Event</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link navSubTitle" href="/">Support Us</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link navSubTitle" href="/">About Us</a>
                </li>

            </ul>
            <form class="d-flex" action="{{ route('event') }}" method="GET">
                <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

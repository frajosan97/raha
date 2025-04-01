<!-- Improved Navbar -->
<nav class="navbar navbar-expand-lg sticky-top py-3">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/images/logos/logo-main.png') }}" alt="Raha Tele Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tour</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Dating Advice</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Singles Near Me</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i> Account
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @auth
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show',auth()->user()->id) }}">
                                <i class="fas fa-user-tie me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                        @else
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i> Register
                            </a>
                        </li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
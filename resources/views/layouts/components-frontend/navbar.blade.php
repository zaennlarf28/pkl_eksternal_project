 {{-- ====== Navbar ====== --}}
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
        <img src="{{ asset('assets/img/logo.png') }}" alt="">
        <h1 class="sitename">FlexStart</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#portfolio">Portfolio</a></li>
          @auth
            <li class="dropdown">
              <a href="#">
                <span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i>
              </a>
              <ul>
                <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item w-100 text-start border-0 bg-transparent">
                      Logout
                    </button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
          @endauth
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <svg width="25" viewBox="0 0 25 42" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.8 0.36L3.4 7.44C0.57 9.69 -0.38 12.48 0.56 15.8C0.69 16.23 1.1 17.79 3.12 19.23C3.81 19.72 5.32 20.38 7.65 21.22L2.63 24.55C0.45 26.3 0.09 28.51 1.56 31.17C2.84 32.82 5.21 33.26 7.09 32.54C8.35 32.06 11.46 30 16.42 26.37C18.03 24.5 18.7 22.45 18.41 20.24C17.96 17.53 16.18 15.58 13.05 14.37L10.92 13.47L18.62 7.98L13.8 0.36Z" fill="#696cff"/>
        </svg>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Dashboard</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>  
    </li>

    <!-- Categories -->
    <li class="menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
      <a href="{{ route('admin.categories.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-category"></i>
        <div>Categories</div>
      </a>
    </li>

    <!-- Models -->
    <li class="menu-item {{ request()->routeIs('admin.models.index') ? 'active' : '' }}">
      <a href="{{ route('admin.models.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-layer"></i>
        <div>Models</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('admin.design_submissions.index') ? 'active' : '' }}">
  <a href="{{ route('admin.design_submissions.index') }}" class="menu-link">
    <i class="menu-icon tf-icons bx bx-image"></i>
    <div>Design Customer</div>
  </a>
</li>



    <!-- Logout -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Akun</span></li>
    <li class="menu-item">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="menu-link border-0 bg-transparent w-100 text-start">
          <i class="menu-icon tf-icons bx bx-power-off text-danger"></i>
          <div>Logout</div>
        </button>
      </form>
    </li>
  </ul>
</aside>

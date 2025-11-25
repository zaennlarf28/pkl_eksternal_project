<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme shadow-sm"
  id="layout-navbar"
>
  <!-- Toggle Mobile Menu -->
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">

    <!-- Judul Admin Panel -->
    <div class="d-none d-md-block">
      <h4 class="mb-0 fw-bold text-dark">Admin Panel</h4>
    </div>

    <ul class="navbar-nav ms-auto flex-row align-items-center">

      <!-- User Dropdown -->
      <li class="nav-item dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
          <div class="avatar avatar-online bg-primary text-white d-flex justify-content-center align-items-center rounded-circle" style="width:40px; height:40px;">
            <i class="bx bx-user fs-4"></i>
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">

          <!-- User Info -->
          <li class="px-3 py-2">
            <div class="d-flex align-items-center">
              <div class="avatar bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width:40px; height:40px;">
                <i class="bx bx-user fs-4"></i>
              </div>
              <div>
                <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                <small class="text-muted">Admin</small>
              </div>
            </div>
          </li>

          <li><hr class="dropdown-divider"></li>

          <!-- Profile -->
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="bx bx-cog me-2"></i> Pengaturan Akun
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <!-- Logout -->
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="bx bx-power-off me-2"></i> Log Out
              </button>
            </form>
          </li>

        </ul>
      </li>
    </ul>
  </div>
</nav>
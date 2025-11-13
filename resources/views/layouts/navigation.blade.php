<nav x-data="{ open: false }" class="bg-white shadow border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
          <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
          <span class="font-semibold text-lg text-gray-800">Karya Unyil</span>
        </a>
      </div>

      <!-- Navigation Links -->
      <div class="hidden sm:flex sm:items-center sm:space-x-8">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
          Dashboard
        </x-nav-link>

        <x-nav-link href="{{ route('models.index') }}" :active="request()->routeIs('models.index')">
          Mockups
        </x-nav-link>

        <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
          About
        </x-nav-link>

        <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
          Contact
        </x-nav-link>
      </div>

      <!-- User Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:space-x-4">
        @auth
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                <span>{{ Auth::user()->name }}</span>
                <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link href="{{ route('profile.edit') }}">
                Profile
              </x-dropdown-link>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link href="{{ route('logout') }}"
                                 onclick="event.preventDefault(); this.closest('form').submit();">
                  Log Out
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Login</a>
        @endauth
      </div>

      <!-- Mobile Menu Button -->
      <div class="flex items-center sm:hidden">
        <button @click="open = !open"
          class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        Dashboard
      </x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('models.index') }}" :active="request()->routeIs('models.index')">
        Mockups
      </x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
        About
      </x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
        Contact
      </x-responsive-nav-link>
    </div>

    <!-- Mobile User Info -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      @auth
        <div class="px-4">
          <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
          <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <x-responsive-nav-link href="{{ route('profile.edit') }}">
            Profile
          </x-responsive-nav-link>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link href="{{ route('logout') }}"
              onclick="event.preventDefault(); this.closest('form').submit();">
              Log Out
            </x-responsive-nav-link>
          </form>
        </div>
      @else
        <div class="px-4 py-2">
          <a href="{{ route('login') }}" class="block text-sm text-gray-600 hover:text-gray-800">Login</a>
        </div>
      @endauth
    </div>
  </div>
</nav>

<nav x-data="{ open: false }" 
     class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl bg-white/80 border-b border-white/20 shadow-[0_2px_10px_rgba(0,0,0,0.05)] transition-all">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">

      <!-- 🔹 Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
        <x-application-logo class="block h-8 w-auto text-purple-600 group-hover:scale-110 transition-transform duration-300" />
        <span class="font-extrabold text-lg bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
          Karya Unyil
        </span>
      </a>

      <!-- 🔹 Navigation Links (Desktop) -->
      <div class="hidden sm:flex sm:items-center sm:space-x-8">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">Home</x-nav-link>
        <x-nav-link href="{{ route('models.index') }}" :active="request()->routeIs('models.index')">Mockups</x-nav-link>
        <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">About</x-nav-link>
        <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">Contact</x-nav-link>
      </div>

      <!-- 🔹 User Menu / Login -->
      <div class="hidden sm:flex sm:items-center sm:space-x-4">
        @auth
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:text-purple-600 transition">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                     class="w-8 h-8 rounded-full border border-gray-200 shadow-sm">
                <span>{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link href="{{ route('profile.edit') }}">⚙️ Profile</x-dropdown-link>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                  🚪 Log Out
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <a href="{{ route('login') }}" 
             class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-full text-sm font-semibold 
                    shadow hover:shadow-lg hover:scale-[1.03] transition-all">
            Login
          </a>
        @endauth
      </div>

      <!-- 🔹 Mobile Menu Button -->
      <div class="flex items-center sm:hidden">
        <button @click="open = !open"
          class="p-2 rounded-md text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition">
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

  <!-- 🔹 Mobile Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden bg-white/90 backdrop-blur-xl border-t border-gray-100">
    <div class="pt-3 pb-4 space-y-2 px-4">
      <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">Home</x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('models.index') }}" :active="request()->routeIs('models.index')">Mockups</x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">About</x-responsive-nav-link>
      <x-responsive-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">Contact</x-responsive-nav-link>
    </div>

    <!-- 🔹 Mobile User Info -->
    <div class="pt-4 pb-3 border-t border-gray-200">
      @auth
        <div class="px-4 flex items-center space-x-3">
          <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
               class="w-10 h-10 rounded-full border border-gray-300 shadow-sm">
          <div>
            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
          </div>
        </div>

        <div class="mt-3 space-y-1">
          <x-responsive-nav-link href="{{ route('profile.edit') }}">⚙️ Profile</x-responsive-nav-link>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
              🚪 Log Out
            </x-responsive-nav-link>
          </form>
        </div>
      @else
        <div class="px-4 py-3">
          <a href="{{ route('login') }}" class="block w-full text-center py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-full font-medium hover:scale-[1.02] transition">
            Login
          </a>
        </div>
      @endauth
    </div>
  </div>
</nav>

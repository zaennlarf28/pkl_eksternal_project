<x-app-layout>
<div class="flex min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">

    {{-- 🌙 SIDEBAR --}}
    <aside class="w-64 bg-white/70 backdrop-blur-lg shadow-lg border-r border-gray-200 hidden md:flex flex-col
               fixed left-0 top-16 bottom-0">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                </svg>
                Category
            </h2>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('models.index') }}" 
               class="block px-4 py-2 rounded-lg transition-all duration-200 
               {{ request('category') ? 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' : 'bg-blue-100 text-blue-700 font-semibold shadow-sm' }}">
               🌐 All
            </a>

            @foreach($categories as $category)
                <a href="{{ route('models.index', ['category' => $category->id]) }}"
                   class="block px-4 py-2 rounded-lg transition-all duration-200 
                   {{ request('category') == $category->id ? 'bg-blue-100 text-blue-700 font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                   {{ $category->name }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- 🧩 MAIN CONTENT --}}
    <main class="flex-1 p-8 ml-64">
        {{-- Header --}}
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                🧩 Pilih Model Produk
            </h1>
            <p class="text-gray-500 mt-2 text-lg">Temukan dan kustomisasi model produk pilihanmu</p>
        </div>

        {{-- 🔍 Search Bar --}}
        <div class="mb-10 flex justify-center">
            <form method="GET" action="{{ route('models.index') }}" 
                  class="relative flex w-full max-w-lg group">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <input type="text" name="search" placeholder="Cari model, misal: box, mug, gantungan..."
                       value="{{ request('search') }}"
                       class="flex-1 border border-gray-300/60 bg-white/80 backdrop-blur-sm rounded-full px-5 py-3 pl-12 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm transition-all duration-200 group-hover:shadow-md">

                <button type="submit"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- 💠 List Mockup --}}
        @if($models->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($models as $model)
                    <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl 
                                transition-all duration-300 overflow-hidden border border-gray-100/70 hover:-translate-y-2">
                        <div class="relative">
                            <img src="{{ $model->image ? asset('storage/'.$model->image) : 'https://via.placeholder.com/400x300?text=No+Image' }}"
                                 alt="{{ $model->name }}"
                                 class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 
                                        group-hover:opacity-100 transition flex items-end justify-center">
                                <a href="{{ route('models.select', $model->id) }}" 
                                   class="mb-5 bg-blue-600/90 text-white font-semibold px-5 py-2.5 rounded-full shadow-md hover:bg-blue-700 transition">
                                   Pilih Model
                                </a>
                            </div>
                        </div>

                        <div class="p-4 text-center">
                            <h2 class="text-lg font-semibold text-gray-800 truncate">{{ $model->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $model->category?->name ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center mt-20">
                <img src="https://illustrations.popsy.co/gray/empty-state.svg" alt="No results" class="w-40 mb-4 opacity-80">
                <p class="text-gray-500 text-lg">Model tidak ditemukan. Coba kata kunci lain 🔍</p>
            </div>
        @endif
    </main>
</div>
</x-app-layout>

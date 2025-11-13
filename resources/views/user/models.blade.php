<x-app-layout>
<div class="flex min-h-screen bg-gray-50">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow-md border-r border-gray-200 hidden md:block">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Kategori</h2>
        </div>

        <nav class="p-3 space-y-1">
            <a href="{{ route('models.index') }}" class="block px-3 py-2 rounded-md hover:bg-blue-100 hover:text-blue-600">All</a>
            @foreach($categories as $category)
                <a href="{{ route('models.index', ['category' => $category->id]) }}"
                   class="block px-3 py-2 rounded-md hover:bg-blue-100 hover:text-blue-600">
                   {{ $category->name }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-6">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">🧩 Pilih Model Produk</h1>
            <p class="text-gray-500 mt-2">Temukan model mockup yang ingin kamu kustomisasi</p>
        </div>

        {{-- List Mockup --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($models as $model)
                <div class="bg-white rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1 overflow-hidden">
                    <div class="relative">
                        <img src="{{ $model->image ? asset('storage/'.$model->image) : 'https://via.placeholder.com/400x300' }}"
                             alt="{{ $model->name }}"
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 hover:opacity-100 flex items-end justify-center transition">
                            <a href="{{ route('models.select', $model->id) }}" 
                               class="mb-4 bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow hover:bg-blue-700">
                               Pilih Model
                            </a>
                        </div>
                    </div>
                    <div class="p-4 text-center">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $model->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $model->category?->name ?? '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</div>
</x-app-layout>

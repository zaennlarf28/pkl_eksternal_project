<x-app-layout>
  <div class="p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">🧩 Pilih Model Produk</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($models as $model)
        <div class="bg-white rounded-2xl shadow hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
          <div class="relative overflow-hidden rounded-t-2xl">
            <img 
              src="{{ $model->image ? asset('storage/'.$model->image) : 'https://via.placeholder.com/400x300' }}" 
              alt="{{ $model->name }}"
              class="w-full h-56 object-cover hover:scale-105 transition-transform duration-300"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 hover:opacity-100 transition duration-300 flex items-end justify-center">
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
  </div>
</x-app-layout>

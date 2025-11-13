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

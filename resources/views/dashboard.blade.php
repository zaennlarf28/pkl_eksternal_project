<x-app-layout>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">My Designs</h1>

      <!-- 🔹 Tombol New Design -->
      <a href="{{ url('models-select')}}" 
         class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
        ➕ New Design
      </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
      @forelse($designs as $design)
        <div class="bg-white p-4 rounded-xl shadow">
          @if($design->thumbnail_path)
            <img src="{{ asset('storage/'.$design->thumbnail_path) }}" class="w-full rounded-lg mb-3">
          @endif

          <h2 class="text-lg font-semibold truncate">{{ $design->title ?? 'Untitled Design' }}</h2>

          <div class="mt-3 flex gap-2">
            <a href="{{ route('editor', $design->id) }}" 
               class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
              ✏️ Edit
            </a>

            <form action="{{ route('designs.destroy', $design->id) }}" method="POST" 
                  onsubmit="return confirm('Hapus desain ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                🗑️ Delete
              </button>
            </form>
          </div>
        </div>
      @empty
        <p class="text-gray-500">Belum ada desain. Yuk buat desain baru!</p>
      @endforelse
    </div>
  </div>
</x-app-layout>

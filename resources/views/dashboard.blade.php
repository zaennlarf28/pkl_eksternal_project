<x-app-layout>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">My Designs</h1>

    {{-- 🔔 Notifikasi sukses --}}
    @if(session('success'))
      <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <a href="{{ route('editor') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ New Design</a>

    <div class="grid grid-cols-3 gap-4 mt-4">
      @foreach($designs as $design)
        <div class="p-4 bg-white shadow rounded relative group">
          <a href="{{ route('editor', $design->id) }}">
            <img 
              src="{{ $design->thumbnail_path ? asset('storage/'.$design->thumbnail_path) : 'https://via.placeholder.com/200' }}" 
              class="w-full h-48 object-cover rounded"
            >
            <div class="mt-2 text-gray-700">{{ $design->title }}</div>
          </a>

          {{-- 🗑 Tombol Delete --}}
          <form 
            action="{{ route('designs.destroy', $design->id) }}" 
            method="POST" 
            onsubmit="return confirm('Are you sure you want to delete this design?');"
            class="absolute top-2 right-2"
          >
            @csrf
            @method('DELETE')
            <button 
              type="submit" 
              class="bg-red-500 text-white text-sm px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition"
            >
              Delete
            </button>
          </form>
        </div>
      @endforeach
    </div>
  </div>
</x-app-layout>

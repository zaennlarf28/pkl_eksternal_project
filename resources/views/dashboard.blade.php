<x-app-layout>
  <div class="relative min-h-screen bg-[#fafaff] overflow-hidden pt-24 md:pt-8">

    {{-- 🧭 Grid 3D Background --}}
    <div class="absolute inset-0 -z-10 bg-[url('https://www.transparenttextures.com/patterns/squares.png')] bg-[length:120px_120px] opacity-10"></div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-[#f7f6ff] to-[#f1f3ff]"></div>

    {{-- 🌟 HERO SECTION --}}
    <section class="max-w-6xl mx-auto px-6 py-20 text-center">
      <h1 class="text-5xl md:text-6xl font-extrabold text-gray-800 leading-tight tracking-tight">
        All about <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-500">mockups</span> 💫<br>
        and <span class="text-purple-600">dieline templates</span> in one place
      </h1>
      <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto">
        Buat dan kelola desain 3D, mockup, dan template kemasanmu dengan gaya profesional.
      </p>

      <div class="mt-10">
        <a href="{{ route('models.index') }}" 
           class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white px-8 py-4 
                  rounded-full font-semibold text-lg shadow-lg hover:shadow-xl hover:scale-[1.03] transition-all duration-300">
          🚀 Mulai Buat Desain
        </a>
      </div>
    </section>

    {{-- 💠 USER DESIGNS / CARD GRID --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
      <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">🎨 Desain Kamu</h2>

      @if($designs->count())
       <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
@foreach($designs as $design)
  <div class="group bg-white border border-gray-200 rounded-3xl shadow-md 
              hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden">

    {{-- HEADER --}}
    <div class="flex items-start justify-between p-6 relative">

      {{-- Title --}}
      <h3 class="text-lg font-semibold text-gray-800 pr-10 leading-snug line-clamp-2">
        {{ $design->title ?? 'Tanpa Judul' }}
      </h3>

      {{-- Buttons Wrapper --}}
      <div class="absolute top-5 right-5 flex items-center gap-2">

        {{-- Open / Edit --}}
        <a href="{{ route('editor', $design->id) }}"
           class="bg-gray-100 hover:bg-gray-200 p-2.5 rounded-full shadow-sm transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700"
               fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5l7 7-7 7" />
          </svg>
        </a>

        {{-- Delete --}}
        <form action="{{ route('designs.destroy', $design->id) }}" 
              method="POST"
              onsubmit="return confirm('Hapus desain ini? Tidak bisa dibatalkan.');">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="bg-red-50 hover:bg-red-100 p-2.5 rounded-full shadow-sm transition text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                   a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4
                   a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h10"/>
            </svg>
          </button>
        </form>

      </div>
    </div>

    {{-- THUMBNAIL --}}
    <div class="w-full aspect-video bg-gray-100 flex items-center justify-center">
      <img src="{{ $design->thumbnail ? asset('storage/'.$design->thumbnail) : 'https://via.placeholder.com/600x400?text=No+Preview' }}"
     class="w-full h-full object-cover">

    </div>

    {{-- BODY --}}
    <div class="p-6">

      <p class="text-gray-500 text-sm mb-4 line-clamp-2">
        {{ $design->description ?? 'Desain kemasan kustom buatanmu.' }}
      </p>

      {{-- LAST SAVED --}}
      <p class="text-xs text-gray-400">
        Disimpan: {{ $design->updated_at->diffForHumans() }}
      </p>

    </div>

  </div>
@endforeach
</div>


      @else
        <div class="text-center text-gray-500 py-10">
          Belum ada desain yang kamu buat. Yuk mulai dari tombol di atas! 🚀
        </div>
      @endif
    </section>

  </div>
</x-app-layout>

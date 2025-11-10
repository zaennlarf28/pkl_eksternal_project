<x-app-layout>
  <div class="p-6 text-center">
    <h1 class="text-2xl font-semibold mb-6">🧊 Pilih Model 3D</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      @foreach(['mug', 'pouch', 'bottle', 'tube'] as $model)
        <a href="{{ route('design.preview3d', ['design' => $design->id, 'model' => $model]) }}"
           class="border rounded-lg shadow hover:shadow-lg transition bg-white p-4 flex flex-col items-center">
          <img src="/images/models/{{ $model }}.jpg" class="w-32 h-32 object-contain mb-2" />
          <span class="capitalize font-medium">{{ $model }}</span>
        </a>
      @endforeach
    </div>
  </div>
</x-app-layout>

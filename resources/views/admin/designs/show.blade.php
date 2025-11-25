@extends('layouts.backend')
@section('title', 'Detail Kiriman Desain')

@section('content')
<div class="p-6 space-y-6">

  <!-- BACK BUTTON -->
  <a href="{{ route('admin.design_submissions.index') }}" 
     class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition">
    <i class="bx bx-chevron-left text-xl"></i>
    Kembali
  </a>

  <!-- HEADER -->
  <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight mb-2">
      {{ $sub->title ?: 'Tanpa Judul' }}
    </h1>

    <div class="flex items-center text-gray-600 text-sm space-x-3">
      <div class="flex items-center gap-1">
        <i class="bx bx-user"></i>
        <span>{{ $sub->user?->name ?? 'Guest' }}</span>
      </div>

      <span class="text-gray-400">•</span>

      <div class="flex items-center gap-1">
        <i class="bx bx-time"></i>
        <span>{{ $sub->sent_at->format('d M Y, H:i') }}</span>
      </div>

      <span class="text-gray-400">•</span>

      <div class="flex items-center gap-1">
        <i class="bx bx-cube"></i>
        <span>{{ $sub->model?->name ?? 'Tanpa Model' }}</span>
      </div>
    </div>
  </div>

  <!-- IMAGE PREVIEW -->
  <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
    <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
      <h2 class="font-semibold text-gray-700">Preview Desain</h2>

      <div class="flex gap-2">
        <a href="{{ asset('storage/' . $sub->image_path) }}" 
           target="_blank"
           class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm">
          Lihat Gambar
        </a>

        <a href="{{ asset('storage/' . $sub->image_path) }}" 
           download
           class="px-3 py-1 text-xs bg-green-600 text-white rounded-md hover:bg-green-700 transition shadow-sm">
          Download
        </a>
      </div>
    </div>

    <div class="p-4">
      <img 
        src="{{ asset('storage/' . $sub->image_path) }}" 
        class="w-full rounded-lg border shadow-sm max-h-[500px] object-contain bg-white"
        alt="Design Image">
    </div>
  </div>

  <!-- FABRIC JSON -->
  <div class="bg-white rounded-2xl shadow border border-gray-100">
    <details class="group">
      <summary class="cursor-pointer select-none p-4 font-semibold text-gray-700 flex justify-between items-center">
        <span>Data Fabric JSON</span>
        <i class="bx bx-chevron-down text-xl group-open:rotate-180 transition-transform"></i>
      </summary>

      <div class="p-4 border-t bg-gray-50">
        <pre class="text-xs bg-gray-900 text-green-300 p-4 rounded-lg overflow-auto max-h-[350px]">
{{ json_encode(json_decode($sub->fabric_json), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
        </pre>
      </div>
    </details>
  </div>

</div>
@endsection

@extends('layouts.backend')
@section('title','Daftar Kiriman Desain')

@section('content')
<div class="p-6">

  <h1 class="text-3xl font-extrabold text-gray-800 mb-6 tracking-tight">
    📥 Kiriman Desain User
  </h1>

  <!-- MAIN CARD -->
  <div class="rounded-2xl p-6 border border-gray-200 shadow-[8px_8px_20px_rgba(0,0,0,0.05),_-8px_-8px_20px_rgba(255,255,255,0.9)] bg-gray-50">

    <div class="overflow-x-auto rounded-xl shadow-inner bg-white border border-gray-100">
      <table class="min-w-full text-sm">

        <!-- HEADER -->
        <thead>
          <tr class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider border-b">
            <th class="px-4 py-3">Preview</th>
            <th class="px-4 py-3">Judul</th>
            <th class="px-4 py-3">User</th>
            <th class="px-4 py-3">Model</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <!-- BODY -->
        <tbody class="divide-y divide-gray-100">

        @foreach($submissions as $s)
          <tr class="hover:bg-gray-50/70 transition">

            <!-- PREVIEW BUTTONS -->
            <td class="px-4 py-3">
              <div class="flex gap-2">
                <a href="{{ asset('storage/' . $s->image_path) }}" 
                   target="_blank"
                   class="px-3 py-1 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold hover:scale-[1.05] transition shadow-sm">
                  Preview
                </a>

                <a href="{{ asset('storage/' . $s->image_path) }}" 
                   download
                   class="px-3 py-1 rounded-lg bg-gradient-to-br from-green-100 to-green-50 text-green-700 border border-green-200 text-xs font-semibold hover:scale-[1.05] transition shadow-sm">
                  Download
                </a>
              </div>
            </td>

            <!-- TITLE -->
            <td class="px-4 py-3 font-semibold text-gray-900">
              {{ $s->title ?: 'Tanpa Judul' }}
            </td>

            <!-- USER -->
            <td class="px-4 py-3 text-gray-700">
              <div class="font-semibold">{{ $s->user?->name ?? 'Guest' }}</div>
              <div class="text-xs text-gray-500">ID: {{ $s->user_id }}</div>
            </td>

            <!-- MODEL -->
            <td class="px-4 py-3 text-gray-700">
              {{ $s->model?->name ?? '-' }}
            </td>

            <!-- DATE -->
            <td class="px-4 py-3 text-gray-600">
              {{ $s->sent_at->format('d M Y, H:i') }}
            </td>

            <!-- STATUS BADGE -->
            <td class="px-4 py-3">
              @php
                $color = match($s->status) {
                  'pending'  => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                  'approved' => 'bg-green-100 text-green-700 border border-green-300',
                  'rejected' => 'bg-red-100 text-red-700 border border-red-300',
                  default    => 'bg-gray-100 text-gray-700 border border-gray-300'
                };
              @endphp

              <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $color }}">
                {{ ucfirst($s->status) }}
              </span>
            </td>

            <!-- ACTION -->
            <td class="px-4 py-3 text-center">
              <div class="flex flex-col space-y-1">

                <!-- DETAIL -->
                <a href="{{ route('admin.design_submissions.show', $s->id) }}"
                   class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700 hover:scale-[1.03] transition shadow">
                  Detail
                </a>

                <!-- DELETE (UPDATE STATUS TO DELETED) -->
                <form action="{{ route('admin.design_submissions.destroy', $s->id) }}" 
      method="POST"
      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
    @csrf
    @method('DELETE')

    <button class="px-3 py-1 bg-red-600 text-white rounded-md text-xs hover:bg-red-700">
        Hapus
    </button>
</form>


              </div>
            </td>

          </tr>
        @endforeach

        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6 flex justify-center">
      {{ $submissions->links() }}
    </div>

  </div>
</div>
@endsection

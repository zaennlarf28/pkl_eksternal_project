@extends('layouts.backend')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">✏️ Edit Model Produk</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.models.update', $model) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Model</label>
                    <input type="text" name="name" value="{{ $model->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $model->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $model->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Model</label><br>
                    @if($model->image)
                        <img src="{{ asset('storage/' . $model->image) }}" class="rounded mb-2" style="width:120px; height:120px; object-fit:cover;">
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.models.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-warning text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

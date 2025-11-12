<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelProduct;
use App\Models\Category;

class ModelProductController extends Controller
{
    // ✅ FITUR LIST + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = ModelProduct::with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $models = $query->latest()->paginate(10)->withQueryString();

        return view('admin.models.index', compact('models'));
    }

    // ✅ HALAMAN TAMBAH MODEL
    public function create()
    {
        $categories = Category::all();
        return view('admin.models.create', compact('categories'));
    }

    // ✅ SIMPAN DATA MODEL BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('models', 'public');
        }

        ModelProduct::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.models.index')->with('success', 'Model berhasil ditambahkan!');
    }

    // ✅ HALAMAN EDIT MODEL
    public function edit(ModelProduct $model)
    {
        $categories = Category::all();
        return view('admin.models.edit', compact('model', 'categories'));
    }

    // ✅ UPDATE DATA MODEL
    public function update(Request $request, ModelProduct $model)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('models', 'public');
        }

        $model->update($data);

        return redirect()->route('admin.models.index')->with('success', 'Model berhasil diperbarui!');
    }

    // ✅ HAPUS DATA MODEL
    public function destroy(ModelProduct $model)
    {
        $model->delete();
        return redirect()->route('admin.models.index')->with('success', 'Model berhasil dihapus!');
    }
}

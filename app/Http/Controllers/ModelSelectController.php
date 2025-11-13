<?php

namespace App\Http\Controllers;

use App\Models\ModelProduct;
use App\Models\Category;
use Illuminate\Http\Request;

class ModelSelectController extends Controller
{
    // Tampilkan semua model, bisa filter kategori
    public function index(Request $request)
    {
        $categories = Category::all(); // Untuk sidebar kategori

        $query = ModelProduct::with('category');

        // Filter by category jika ada query parameter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $models = $query->latest()->get();

        return view('user.models', compact('models', 'categories'));
    }

    // Saat user memilih salah satu model
    public function select(ModelProduct $model)
    {
        $design = auth()->user()->designs()->create([
            'title' => 'New Design - ' . $model->name,
            'fabric_json' => '{}',
        ]);

        return redirect()->route('editor', [
            'id' => $design->id,
            'model' => $model->id
        ]);
    }

    public function search(Request $request)
{
    $query = ModelProduct::with('category');

    if ($request->has('q') && $request->q != '') {
        $search = $request->q;
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', function($c) use ($search) {
                  $c->where('name', 'like', "%{$search}%");
              });
    }

    $models = $query->latest()->get();

    // kembalikan partial HTML (Blade)
    return view('user.partials.model-cards', compact('models'))->render();
}

}

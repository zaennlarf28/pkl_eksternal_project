<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ModelProduct;
use App\Models\Category;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest()->get();

        $query = ModelProduct::with('category');

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $models = $query->latest()->get();

        return view('frontend.models', compact('models', 'categories'));
    }

    public function select($id)
    {
        $model = ModelProduct::with('category')->findOrFail($id);
        return view('frontend.models-detail', compact('model'));
    }
}

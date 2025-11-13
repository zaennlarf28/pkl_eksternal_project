<?php

namespace App\Http\Controllers;

use App\Models\ModelProduct;
use Illuminate\Http\Request;

class ModelSelectController extends Controller
{
    public function index()
    {
        $models = ModelProduct::with('category')->latest()->get();
        return view('user.models', compact('models'));
    }

    public function select(ModelProduct $model)
    {
        // Buat design baru untuk user
        $design = auth()->user()->designs()->create([
            'title' => 'New Design - ' . $model->name,
            'fabric_json' => '{}',
        ]);

        // Redirect ke editor + kirim ID model
        return redirect()->route('editor', ['id' => $design->id, 'model' => $model->id]);
    }
}

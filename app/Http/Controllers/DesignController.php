<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\ModelProduct;
use Illuminate\Http\Request;

class DesignController extends Controller
{
   
   public function editor(Request $request, $id = null)
{
    $design = $id ? Design::findOrFail($id) : null;

    // cari model
    $model = null;
    if ($request->has('model')) {
        $model = ModelProduct::find($request->get('model'));
    } elseif ($design && $design->model_id) {
        $model = $design->model;
    }

    return view('editor', compact('design', 'model'));
}

public function save(Request $request)
{
    $data = $request->validate([
        'id' => 'nullable|exists:designs,id',
        'title' => 'required|string|max:255',
        'fabric_json' => 'required|string',
        'model_id' => 'nullable|exists:model_products,id',
        'thumbnail' => 'nullable|string' // <= penting!
    ]);

    // create atau find
    $design = $request->id
        ? auth()->user()->designs()->findOrFail($request->id)
        : auth()->user()->designs()->create();

    // update basic fields
    $design->update([
        'title' => $data['title'],
        'fabric_json' => $data['fabric_json'],
        'model_id' => $data['model_id'] ?? null,
    ]);

    // ===== SIMPAN THUMBNAIL CANVAS =====
    if ($request->thumbnail) {

        $image = $request->thumbnail;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'thumbnail_' . time() . '.png';

        \Storage::disk('public')->put('designs/' . $imageName, base64_decode($image));

        $design->thumbnail = 'designs/' . $imageName;
        $design->save();
    }

    return response()->json([
        'success' => true,
        'id' => $design->id,
        'title' => $design->title,
    ]);
}

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $path = $request->file('image')->store('uploads/designs', 'public');
        $url = asset('storage/' . $path);

        return response()->json(['url' => $url]);
    }

    public function chooseModel($id)
    {
        $design = Design::findOrFail($id);
        return view('design.choose-model', compact('design'));
    }

    public function preview3D(Design $design, $model)
    {
        $modelPath = match ($model) {
            'mug' => 'models/mug.glb',
            'pouch' => 'models/pouch.glb',
            'bottle' => 'models/bottle.glb',
            'tube' => 'models/tube.glb',
            default => abort(404, 'Model not found'),
        };

        return view('design.preview3d', compact('design', 'model', 'modelPath'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function editor($id = null)
    {
        $design = $id ? Design::findOrFail($id) : null;
        return view('editor', compact('design'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'fabric_json' => 'required|json',
        ]);

        $design = Design::updateOrCreate(
            ['id' => $request->id ?: null, 'user_id' => auth()->id()],
            [
                'title' => $validated['title'],
                'fabric_json' => $validated['fabric_json'],
                'user_id' => auth()->id(),
            ]
        );

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

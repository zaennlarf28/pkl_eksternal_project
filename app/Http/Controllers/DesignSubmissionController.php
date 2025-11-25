<?php

// app/Http/Controllers/DesignSubmissionController.php
namespace App\Http\Controllers;

use App\Models\DesignSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignSubmissionController extends Controller
{
    public function store(Request $request)
    {
        // validasi
        $request->validate([
            'image' => 'required|file|mimes:png,jpg,jpeg|max:10240',
            'title' => 'nullable|string|max:255',
            'model_id' => 'nullable|integer',
            'fabric_json' => 'nullable|string',
        ]);

        $user = $request->user();

        // simpan file
        $file = $request->file('image');
        $filename = 'designs/' . date('Y/m') . '/' . Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put($filename, file_get_contents($file->getRealPath()));

        // simpan record
        $submission = DesignSubmission::create([
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'model_id' => $request->input('model_id'),
            'image_path' => $filename,
            'fabric_json' => $request->input('fabric_json'),
            'status' => 'pending',
            'sent_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'id' => $submission->id,
            'image_url' => asset('storage/' . $filename),
        ]);
    }
}

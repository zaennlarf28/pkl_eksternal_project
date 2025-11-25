<?php

// app/Http/Controllers/Admin/DesignSubmissionController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignSubmissionController extends Controller
{

    public function index(Request $request) {
        $q = DesignSubmission::with('user','model')->latest();
        if ($request->filled('status')) $q->where('status', $request->status);
        $submissions = $q->paginate(25);
        return view('admin.designs.index', compact('submissions'));
    }

    public function show($id) {
        $sub = DesignSubmission::with('user','model')->findOrFail($id);
        return view('admin.designs.show', compact('sub'));
    }

    // optional: mark as seen / download
    public function updateStatus(Request $request, $id) {
        $sub = DesignSubmission::findOrFail($id);
        $sub->status = $request->status;
        $sub->save();
        return back()->with('success','Status updated');
    }

     public function destroy($id)
    {
        $sub = DesignSubmission::findOrFail($id);

        // hapus file jika ada
        if ($sub->image_path && Storage::disk('public')->exists($sub->image_path)) {
            Storage::disk('public')->delete($sub->image_path);
        }

        $sub->delete();

        return redirect()->route('admin.design_submissions.index')
                         ->with('success', 'Kiriman desain berhasil dihapus.');
    }
}


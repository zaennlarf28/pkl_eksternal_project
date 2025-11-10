<?php

namespace App\Http\Controllers;

use App\Models\Design;

class DashboardController extends Controller
{
    public function index()
    {
        $designs = Design::where('user_id', auth()->id())->latest()->get();
        return view('dashboard', compact('designs'));
    }

    public function destroy(Design $design)
{
    // Pastikan user hanya bisa hapus desain miliknya
    if ($design->user_id !== auth()->id()) {
        abort(403);
    }

    // Hapus file thumbnail jika ada
    if ($design->thumbnail_path && \Storage::exists('public/' . $design->thumbnail_path)) {
        \Storage::delete('public/' . $design->thumbnail_path);
    }

    // Hapus record
    $design->delete();

    // Redirect dengan notifikasi sukses
    return redirect()->route('dashboard')->with('success', 'Design deleted successfully!');
}

}


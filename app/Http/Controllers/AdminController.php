<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Permission;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        $videos = Video::all();
        $requests = Permission::with(['user', 'video'])->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('videos', 'requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required',
        ]);

        Video::create([
            'title' => $request->title,
            'url' => $request->url,
        ]);

        return back()->with('success', 'Video added successfully.');
    }

    public function approveRequest(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'duration' => 'required|integer|min:1'
        ]);

        $expiredAt = now()->addMinutes((int) $request->duration);
        $permission->update([
            'status' => 'approved',
            'expires_at' => $expiredAt,
        ]);

        return back()->with('success', 'Akses diberikan hingga ' . $expiredAt->format('d M Y H:i'));
    }

    public function rejectRequest(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update([
            'status' => 'rejected',
            'expires_at' => null,
        ]);

        return back()->with('success', 'Akses ditolak.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required',
        ]);

        $video->update([
            'title' => $request->title,
            'url' => $request->url,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return back()->with('success', 'Video deleted successfully.');
    }
}

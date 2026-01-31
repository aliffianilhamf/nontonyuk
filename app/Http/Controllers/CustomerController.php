<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        $videos = Video::all();

        $myPermissions = Permission::where('user_id', Auth::id())
            ->get()
            ->keyBy('video_id');

        return view('customer.videos.index', compact('videos', 'myPermissions'));
    }

    public function requestAccess($videoId)
    {
        $permission = Permission::where('user_id', Auth::id())
            ->where('video_id', $videoId)
            ->first();

        // jika ada
        if ($permission) {
            // re-request
            if ($permission->status == 'expired' || $permission->expires_at < now()) {
                $permission->update([
                    'status' => 'pending',
                    'expires_at' => null,
                ]);
                return back()->with('success', 'Akses ulang telah diminta. Silakan tunggu persetujuan admin.');
            }
            // jika sudah pending atau approved
            return back()->with('info', 'Anda sudah memiliki permintaan akses untuk video ini.');
        }

        // Buat baru jika tidak ada
        Permission::create([
            'user_id' => Auth::id(),
            'video_id' => $videoId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Akses telah diminta. Silakan tunggu persetujuan admin.');
    }

    public function watchVideo($videoId)
    {
        $permission = Permission::where('user_id', Auth::id())
            ->where('video_id', $videoId)
            ->first();



        if (!$permission || $permission->status != 'approved') {
            return back()->with('error', 'Anda tidak memiliki akses untuk menonton video ini.');
        }

        if (now()->greaterThan($permission->expires_at)) {
            $permission->update(['status' => 'expired']);
            return back()->with('error', 'Akses Anda untuk menonton video ini telah kedaluwarsa.');
        }

        $video = Video::findOrFail($videoId);

        $remainingSeconds = now()->diffInSeconds($permission->expires_at, false);
        return view('customer.videos.watch', compact('video', 'remainingSeconds'));
    }
}

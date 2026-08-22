<?php

namespace App\Http\Controllers\Backend\Banners;

use App\Http\Controllers\Controller;
use App\Models\Backend\Banners\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannersController extends Controller
{
    public function index()
    {
        $banners = Banners::latest()->paginate(15);
        return view('backend.banners.banner', compact('banners'));
    }

    public function create()
    {
        return view('backend.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        if (!$request->hasFile('image') && !$request->hasFile('video')) {
            return back()->withErrors(['image' => 'Image অথবা Video যেকোনো একটা দিতে হবে।'])->withInput();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/banners/images');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $this->uploadFile($request->file('video'), 'uploads/banners/videos');
        }

        Banners::create($validated);

        return redirect()->route('dashboard.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banners $banner)
    {
        return view('backend.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banners $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteFile($banner->image, 'banners/images');
            $validated['image'] = $this->uploadFile($request->file('image'), 'uploads/banners/images');
        }

        if ($request->hasFile('video')) {
            $this->deleteFile($banner->video, 'banners/videos');
            $validated['video'] = $this->uploadFile($request->file('video'), 'uploads/banners/videos');
        }

        $banner->update($validated);

        return redirect()->route('dashboard.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banners $banner)
    {
        $this->deleteFile($banner->image, 'banners/images');
        $this->deleteFile($banner->video, 'banners/videos');
        $banner->delete();

        return back()->with('success', 'Banner deleted successfully.');
    }

    private function uploadFile($file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);
        return $folder . '/' . $filename; // e.g. uploads/banners/images/xxx.jpg
    }

    private function deleteFile(?string $path, string $subfolder): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

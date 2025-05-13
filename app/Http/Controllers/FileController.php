<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $disk_name = $request->input('disk') ?: 'public';
        $folder = $request->input('folder') ?: 'avatars';
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $name = $request->input('name') ?: $originalName;
        /** @var \League\Flysystem\Local\LocalFilesystemAdapter|\Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($disk_name);

        $path = $file->store($folder, ['disk' => $disk_name]);
        $md5_hash = md5_file($disk->path($path));
        $is_image = Str::startsWith($file->getMimeType(), 'image');
        if ($is_image) {
            $image_file_full_path = $file->getRealPath();
            $image = ImageManager::imagick()->read($image_file_full_path);
            $width = min($image->width(), 800);
            $height = min($image->height(), 500);
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imagedata = (string) $image->toJpeg();
            $disk->put($path, $imagedata);
        }
        $entry = File::where('hash', $md5_hash)->first();
        if (!$entry) {
            $entry = File::create([
                'name' => $name,
                'original_name' => $originalName,
                'ext' => $file->getClientOriginalExtension(),
                'disk' => $disk_name,
                'path' => $path,
                'size' => $disk->size($path),
                'mime' => $disk->mimeType($path),
                'hash' => $md5_hash,
                'uuid' => (string) Str::uuid(),
                'url' => '/storage/' . $folder . '/' . $path,
            ]);
        }

        $entry->update([
            'name' => $name,
            'path' => $path,
        ]);
        return new FileResource($entry);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        $disk = Storage::disk($file->disk);
        $path = $file->path;
        $content = $disk->get($path);
        return response($content)->header('Content-Type', $file->mime);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $data = $request->only('name');

        $file->update($data);
        return new FileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        try {
            $file->delete();
            return response(['status' => true], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, 'foreign key')) {
                $message = "File này đang được liên kết với dữ liệu khác";
            }
            return response(['message' => $message], 403);
        }
    }
}

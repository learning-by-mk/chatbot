<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class File extends Model
{
    use HasFactory;
    protected $guarded = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createThumbImageFile(?int $width = 278, ?int $height = 193): self|bool
    {
        $image_file = $this;
        if (!in_array($this->mime, ['image/jpeg', 'image/png'])) {
            return false;
        }
        $image_file_full_path = $image_file->getFullPath();
        $image = ImageManager::imagick()->read($image_file_full_path);
        $image = $image->resize($width, $height); // $image->resizeDown(278, 193);
        $imagedata = (string) $image->toJpeg();
        $disk_name = $image_file->disk;
        /** @var \League\Flysystem\Local\LocalFilesystemAdapter|\Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($disk_name);
        $thumb_image_name = $image_file->name . '-' . (string) Str::uuid() . '-resized.jpg';
        $path = $thumb_image_path = dirname($image_file->path) . '/thumbs/' . $thumb_image_name;
        $disk->put($thumb_image_path, $imagedata);

        $info = pathinfo($disk->path($thumb_image_path));
        $md5_hash = md5_file($disk->path($thumb_image_path));

        $entry = File::firstOrcreate(['hash' => $md5_hash], [
            'name' => $thumb_image_name,
            'original_name' => $thumb_image_name,
            'ext' => $info['extension'],
            'disk' => $disk_name,
            'path' => $path,
            'size' => $disk->size($path),
            'mime' => $disk->mimeType($path),
            'hash' => $md5_hash,
        ]);
        return $entry;
    }
}

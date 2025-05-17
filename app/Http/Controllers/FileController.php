<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
            // Lưu file vào storage trước
            $originalPath = $path;
            $originalMime = $file->getMimeType();

            if (str_contains($originalMime, 'image/jpeg') || str_contains($originalMime, 'image')) {
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
                    'url' => Storage::url($path),
                    'absolute_url' => env('APP_URL') . Storage::url($path),
                ]);
            } else {
                $isPdf = strtolower($file->getClientOriginalExtension()) === 'pdf' ||
                    $originalMime === 'application/pdf';

                // Nếu không phải PDF, chuyển đổi sang PDF
                if (!$isPdf) {
                    // Lưu file gốc trước
                    $disk->put($originalPath, file_get_contents($file));

                    // Tạo tên file PDF mới
                    $pdfName = pathinfo($originalName, PATHINFO_FILENAME) . '_converted.pdf';
                    $pdfPath = 'uploads/' . date('Y/m/d') . '/' . $md5_hash . '_' . $pdfName;

                    // Chuyển đổi file sang PDF
                    $this->convertToPdf($disk, $originalPath, $pdfPath, $originalMime);

                    // Cập nhật thông tin file
                    $path = $pdfPath;
                    $originalName = $pdfName;
                } else {
                    // Nếu đã là PDF, lưu trực tiếp
                    $disk->put($path, file_get_contents($file));
                }

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
                    'url' => Storage::url($path),
                    'absolute_url' => env('APP_URL') . Storage::url($path),
                    'absolute_path_pdf' => env('APP_URL') . Storage::url($pdfPath),
                    'path_pdf' => $pdfPath,
                    'url_pdf' => Storage::url($pdfPath),
                    'ext_pdf' => 'pdf',
                ]);
            }
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

    /**
     * Kiểm tra và đảm bảo font Unicode đã được cài đặt
     */
    private function ensureUnicodeFont()
    {
        $dejavu_path = public_path('fonts/DejaVu/DejaVuSans.ttf');

        // Đảm bảo thư mục tồn tại
        if (!file_exists(dirname($dejavu_path))) {
            mkdir(dirname($dejavu_path), 0755, true);
        }

        // Không tải font từ URL nữa vì không ổn định
        // Kiểm tra xem file font đã tồn tại chưa
        if (!file_exists($dejavu_path)) {
            Log::warning('Font DejaVu Sans không tồn tại tại đường dẫn: ' . $dejavu_path);
            // Ghi log hướng dẫn
            Log::warning('Vui lòng tải font DejaVu Sans và DejaVu Sans Bold vào thư mục: ' . dirname($dejavu_path));
        }
    }

    /**
     * Chuyển đổi file sang định dạng PDF
     *
     * @param \Illuminate\Filesystem\FilesystemAdapter $disk
     * @param string $sourcePath
     * @param string $targetPath
     * @param string $mimeType
     * @return bool
     */
    private function convertToPdf($disk, $sourcePath, $targetPath, $mimeType)
    {
        try {
            // Lấy nội dung file
            $content = $disk->get($sourcePath);

            // Tạo nội dung HTML đơn giản với tiêu đề và nội dung
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @font-face {
                        font-family: "DejaVu Sans";
                        font-style: normal;
                        font-weight: normal;
                        src: url("' . public_path('fonts/DejaVu/DejaVuSans.ttf') . '");
                    }
                    @font-face {
                        font-family: "DejaVu Sans";
                        font-style: normal;
                        font-weight: bold;
                        src: url("' . public_path('fonts/DejaVu/DejaVuSans-Bold.ttf') . '");
                    }
                    * {
                        font-family: "DejaVu Sans", sans-serif !important;
                    }
                    body {
                        padding: 20px;
                        font-size: 12pt;
                    }
                    h1 {
                        color: #333;
                        font-size: 18pt;
                    }
                    pre {
                        white-space: pre-wrap;
                        word-wrap: break-word;
                        border: 1px solid #ddd;
                        padding: 10px;
                        background-color: #f9f9f9;
                        font-size: 10pt;
                    }
                </style>
            </head>
            <body>
                <h1>Tài liệu được chuyển đổi</h1>
                <hr>
                <div>' . nl2br(htmlspecialchars($content)) . '</div>
            </body>
            </html>';

            // Sử dụng loadHTML thay vì loadFile để tránh vấn đề về quyền truy cập
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('a4');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true
            ]);

            // Tạo một tệp PDF tạm thời trong thư mục storage
            $tempPdfFile = storage_path('app/temp/temp_' . uniqid() . '.pdf');

            // Đảm bảo thư mục tồn tại
            if (!file_exists(dirname($tempPdfFile))) {
                mkdir(dirname($tempPdfFile), 0777, true);
            }

            // Lưu file PDF
            $pdf->save($tempPdfFile);

            // Nếu tạo PDF thành công, lưu vào disk
            if (file_exists($tempPdfFile)) {
                $pdfContent = file_get_contents($tempPdfFile);
                $disk->put($targetPath, $pdfContent);

                // Xóa tệp tạm
                @unlink($tempPdfFile);

                return true;
            }

            // Xóa tệp tạm nếu có lỗi
            if (file_exists($tempPdfFile)) {
                @unlink($tempPdfFile);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Lỗi chuyển đổi PDF: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return false;
        }
    }
}

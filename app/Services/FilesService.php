<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FilesService
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/public');
        $this->path .= '/files/';
    }

    public static function generateFile($number, $monkey_id): string
    {
       $name = ExperimentService::generateFile($number, $monkey_id);
       return $name;
    }

    public function downloadFile(string $id): BinaryFileResponse
    {
        $file = File::find($id);
        $path = $this->path . $file->name . '.xlsx';
        return response()->download($path);
    }

    public function deleteFile(string $id): JsonResponse
    {
        $file = File::find($id);

        $path = $this->path . $file->name . '.xlsx';

        Storage::delete($path);

        return response()->json(['status' => $file->delete() ? 'ok' : 'error']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Figure;
use App\Models\FigureResult;
use App\Models\File;
use App\Models\Monkey;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function generateFile($number, string $monkeyId): JsonResponse
    {
        $name = FilesService::generateFile($number, $monkeyId);

        $response = ['status' => 'ok', 'name' => $name];

        return response()->json($response);
    }

    public function downloadFile(string $id): BinaryFileResponse
    {
        $file = new FilesService();
        return $file->downloadFile($id);
    }

    public function deleteFile(string $id): JsonResponse
    {
        $file = new FilesService();
        return $file->deleteFile($id);
    }
}

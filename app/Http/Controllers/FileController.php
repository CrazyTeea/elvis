<?php

namespace App\Http\Controllers;

use App\Models\Figure;
use App\Models\FigureResult;
use App\Models\File;
use App\Models\Monkey;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/public');
        $this->path .= '/files/';
    }

    /**
     * @throws \Throwable
     */
    public function generateFile(string $monkeyId): JsonResponse
    {
        $getDiametr = function (FigureResult $result) {
            return $result->figure->name == 'ellipse' ? $result->w * 2 : $result->w;
        };

        $center = function (FigureResult $result) use ($getDiametr) {
            $h = in_array($result->figure->name, ['rectangle2', 'rectangle3']) ? $result->h : $result->w;
            $cx = $result->x + $result->w;
            $cy = $result->y + $h;
            return compact('cx', 'cy');
        };

        $name = DB::transaction(function () use ($monkeyId, $center, $getDiametr) {
            $monkey = Monkey::find($monkeyId);

            $spr = new Spreadsheet();

            $results = FigureResult::whereExperimentId($monkey->lastExperiment()->id)->get();

            $reac_time = $results->reduce(function (float $reac_time, FigureResult $result) {
                    return $reac_time + ($result->reaction_time != -1 ? $result->reaction_time : 0);
                }, 0) / (max($results->count(), 1));

            $reacs = 0;
            $arr = [];
            $figures = [];
            foreach ($results as $result) {
                if ($result->reaction_time != -1) {
                    $reacs++;
                }
                $c = $center($result);
                $figures[] = [
                    $result->figure->name,
                    (string)$result->figure->size_min,
                    (string)$result->figure->size_max,
                    (string)$result->figure->brightness_min,
                    (string)$result->figure->brightness_max,
                ];
                $arr[] = [
                    $result->figure->name,
                    Figure::COLORS[$result->color],
                    $result->figure->brightness,
                    in_array($result->figure->name, ['rectangle2', 'rectangle3']) ? $result->h : $result->w,
                    $c['cx'] + $result->x_oblast,
                    $c['cy'] + $result->y_oblast,
                    $result->reaction_time == -1 ? '0' : $result->reaction_time,
                    $result->x_click,
                    $result->y_click,
                    $result->reaction_time == -1 ? '0' : '1',
                ];
            }
            $arr[] = ['', '', '', '', '', '', 'правильные ответы', "$reacs / {$results->count()}"];
            $arr[] = ['', '', '', '', '', '', 'время реакции', $reac_time];

            $lastExperiment = $monkey->lastExperiment();
            $arr2 = [
                ['elvis_id', 'date', 'figure_count', ' ', 'br_min', 'br_max', 'x1', 'x2', 'y1', 'y2',],
                [
                    $monkey->elvis_id,
                    $lastExperiment->created_at,
                    $lastExperiment->figureResults()->count(),
                    '',
                    $lastExperiment->x2 ? $lastExperiment->br_min : $lastExperiment->oblast->br_min,
                    $lastExperiment->x2 ? $lastExperiment->br_max : $lastExperiment->oblast->br_max,
                    $lastExperiment->x2 ? $lastExperiment->x1 : $lastExperiment->oblast->x1,
                    $lastExperiment->x2 ? $lastExperiment->x2 : $lastExperiment->oblast->x2,
                    $lastExperiment->x2 ? $lastExperiment->y1 : $lastExperiment->oblast->y1,
                    $lastExperiment->x2 ? $lastExperiment->y2 : $lastExperiment->oblast->y2,
                ],
                [''],
                ['figure', 'size_min', 'size_max', 'br_min', 'br_max',],
                ...$figures,
                [''],
                ['figure_name', 'color', 'br', 'size', 'x', 'y', 'reaction', 'coord_x', 'coord_y'],
                ...$arr

            ];

            $spr->getActiveSheet()
                ->fromArray($arr2, null, 'A1')
                ->getStyle('A1:J30')
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spr->getActiveSheet()->getColumnDimension('G')->setWidth(140, 'px');
            $spr->getActiveSheet()->getColumnDimension('B')->setWidth(110, 'px');


            $xlsx = IOFactory::createWriter($spr, IOFactory::WRITER_XLSX);

            $name = date("Y_m_d_H_i_s");

            File::query()->insert(['name' => $name, 'experiment_id' => $monkey->lastExperiment()->id, 'monkey_id' => $monkeyId]);

            $name = "{$this->path}{$name}";
            $xlsx->save("$name.xlsx");
            return $name;
        });
        $response = ['status' => 'ok', 'name' => $name];


        return response()->json($response);
    }

    public
    function downloadFile(string $id): BinaryFileResponse
    {
        $file = File::find($id);
        $path = $this->path . $file->name . '.xlsx';
        return response()->download($path);
    }

    public
    function deleteFile(string $id): JsonResponse
    {
        $file = File::find($id);

        $path = $this->path . $file->name . '.xlsx';

        Storage::delete($path);

        return response()->json(['status' => $file->delete() ? 'ok' : 'error']);
    }
}

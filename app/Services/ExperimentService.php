<?php

namespace App\Services;

use App\Models\Experiment;
use App\Models\Figure;
use App\Models\FigureResult;
use App\Models\File;
use App\Models\Helpers;
use App\Models\Monkey;
use App\Models\Oblast;
use App\Models\Position;
use App\Models\Stimul;
use App\Models\TimeLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExperimentService
{
    private int $number;
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/public');
        $this->path .= '/files/';
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    private function getId(&$arr): string|int|null
    {
        $experimentId = null;
        if (isset($arr['id'])) {
            $experimentId = $arr['id'];
            unset($arr['id']);
        }
        return $experimentId;
    }

    public static function storeFromRequest(Request $request)
    {
        $service = new ExperimentService();
        $service->setNumber($request->input('number', $request->input('experiment.number')));
        $method = 'store' . $service->getNumber();
        switch ($service->getNumber()) {
            case 1:
            {
                return $service->$method(
                    experiment: $request->only(['id', 'number', 'name', 'name', 'monkey_id']),
                    oblast: $request->only(['br_min', 'br_max', 'x1', 'x2', 'y1', 'y2']),
                );
            }
            case 2:
            {
                return $service->$method($request);
            }
            default:
            {
                return ['error' => 404];
            }
        }
    }

    private function store1(array $oblast, array $experiment): array
    {
        $id = $this->getId($experiment);
        $experimentModel = Experiment::updateOrCreate(['id' => $id], $experiment);
        $oblast['experiment_id'] = $experimentModel->id;
        $oblastModel = Oblast::updateOrCreate(['experiment_id' => $experimentModel->id], $oblast);
        return ['experiment' => $experimentModel->toArray(), 'oblast' => $oblastModel->toArray()];
    }

    private function store2(Request $request): array
    {
        $helpers = $request->input('helpers');
        $stimul = $request->input('stimul');
        $positions = $request->input('positions');
        $experiment = $request->input('experiment');
        $experimentId = $this->getId($experiment);
        $line = $request->get('line');

        $experimentModel = Experiment::updateOrCreate(['id' => $experimentId], $experiment);

        foreach ($helpers as $helper) {
            Helpers::create([...$helper, 'experiment_id' => $experimentModel->id]);
        }

        TimeLine::create(['experiment_id' => $experimentModel->id, 'data' => json_encode($line)]);

        Stimul::create([...$stimul, 'experiment_id' => $experimentModel->id]);

        foreach ($positions as $position) {
            Position::create(['name' => $position, 'experiment_id' => $experimentModel->id]);
        }

        return ['experiment' => $experimentModel->toArray()];

    }

    public static function generateFile($number, $monkey_id)
    {
        $service = new ExperimentService();
        $service->setNumber($number);
        $method = 'generateFile' . $number;
        return $service->$method($monkey_id);
    }

    /**
     * @throws \Throwable
     */
    private function generateFile1($monkeyId): string
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

        return DB::transaction(function () use ($monkeyId, $center, $getDiametr) {
            $monkey = Monkey::find($monkeyId);

            $spr = new Spreadsheet();
            $lastExperiment = $monkey->lastExperiment(1);
            $lastExperimentId = $lastExperiment->id;

            $results = FigureResult::whereExperimentId($lastExperimentId)->get();

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

            File::query()->insert(['name' => $name, 'experiment_id' => $lastExperimentId, 'monkey_id' => $monkeyId]);

            $xlsx->save("{$this->path}{$name}.xlsx");
            return $name;
        });
    }

    private function generateFile2($monkeyId): string
    {
        $monkey = Monkey::find($monkeyId);
        $lastExperiment = $monkey->lastExperiment(2);
        $lastExperimentId = $lastExperiment->id;
        $spr = new Spreadsheet();

        $line = $lastExperiment->timeLine->parsed_data;

        $arr = [
            ['elvis_id', 'date',],
            [''],
            ['countProbs', 'startDelay', 'startHelp', 'waitQuestion', 'stopDelay'],
            [
                $line['countProbs'],
                $line['startDelay'],
                $line['startHelp']['min'] . '/' . $line['startHelp']['max'],
                $line['waitQuestion']['min'] . '/' . $line['waitQuestion']['max'],
                $line['stopDelay']['min'] . '/' . $line['stopDelay']['max'],
            ]
        ];

        $spr->getActiveSheet()
            ->fromArray($arr, null, 'A1')
            ->getStyle('A1:J30')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $name = date("Y_m_d_H_i_s");

        File::query()->insert(['name' => $name, 'experiment_id' => $lastExperimentId, 'monkey_id' => $monkeyId]);
        $xlsx = IOFactory::createWriter($spr, IOFactory::WRITER_XLSX);
        $xlsx->save("{$this->path}{$name}.xlsx");
        return $name;
    }
}

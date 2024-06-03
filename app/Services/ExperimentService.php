<?php

namespace App\Services;

use App\Models\Experiment;
use App\Models\Helpers;
use App\Models\Oblast;
use App\Models\Position;
use App\Models\Stimul;
use Illuminate\Http\Request;

class ExperimentService
{
    public function __construct(private int $number)
    {
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
        $service = new ExperimentService($request->input('number', $request->input('experiment.number')));
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

        $experimentModel = Experiment::updateOrCreate(['id' => $experimentId], $experiment);

        foreach ($helpers as $helper) {
            Helpers::create([...$helper, 'experiment_id' => $experimentModel->id]);
        }

        Stimul::create([...$stimul, 'experiment_id' => $experimentModel->id]);

        foreach ($positions as $position) {
            Position::create(['name' => $position, 'experiment_id' => $experimentModel->id]);
        }

        return ['experiment' => $experimentModel->toArray()];

    }
}

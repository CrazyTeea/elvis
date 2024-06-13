<?php

namespace App\Http\Controllers;

use App\Models\Exp2Results;
use App\Models\Experiment;
use App\Models\Figure;
use App\Models\FigureResult;
use App\Services\ExperimentService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;

class ExperimentController extends Controller
{
    public function store(Request $request): array
    {
        return ExperimentService::storeFromRequest($request);
    }

    public function sendStimul(Request $request)
    {
        try {
            $response = Http::post('http://localhost:8001/command/' . $request->input('command'), $request->post());

        } catch (ConnectionException $exception) {
            return Response::json(['message' => 'Нет связи с COM PROXY'], 400);
        }
        if (!$response->successful()) {
            return Response::json(['message' => $response->json('message')], 400);
        }
        return $response->json();
    }

    public function storeExp2Res(Request $request)
    {
        $experiment_id = $request->input('experiment_id');
        $stimul_id = $request->input('stimul_id');
        $position_id = $request->input('position_id');
        $helper_id = $request->input('helper_id');
        $x = $request->input('x');
        $y = $request->input('y');
        $reaction = $request->input('reaction');

        return Exp2Results::create(compact('experiment_id',
            'stimul_id', 'position_id', 'helper_id', 'x', 'y', 'reaction'));
    }

    public function test()
    {
        return Experiment::find(410)->position_strings;
    }

    public function storeFigures(Request $request): array
    {
        $data = $request->only(["figure", "figure_result"]);
        $ret = ['figure' => [], 'figure_result' => []];

        foreach ($data['figure'] as $figure) {
            $f = json_decode($figure, true);
            $id = $f['id'] ?? null;
            if (is_array($f['colors'])) {
                $f['colors'] = array_reduce($f['colors'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            if (is_array($f['angles'])) {
                $f['angles'] = array_reduce($f['angles'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            if (is_array($f['xx'])) {
                $f['xx'] = array_reduce($f['xx'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            if (is_array($f['yy'])) {
                $f['yy'] = array_reduce($f['yy'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            if (is_array($f['ww'])) {
                $f['ww'] = array_reduce($f['ww'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            if (is_array($f['hh'])) {
                $f['hh'] = array_reduce($f['hh'], function ($carry, $item) {
                    return $carry .= $item . ',';
                }, '');
            }
            $ret['figure'][] = Figure::updateOrCreate(['id' => $id], $f);
        }
        if (isset($data['figure_result'])) {
            foreach ($data['figure_result'] as $figure_result) {
                $f = json_decode($figure_result, true);
                $id = $f['id'] ?? null;
                $ret['figure_result'][] = FigureResult::updateOrCreate(['id' => $id], $f);
            }
        }

        return $ret;

    }
}

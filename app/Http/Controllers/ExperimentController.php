<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use App\Models\Figure;
use App\Models\FigureResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExperimentController extends Controller
{
    public function store(Request $request): Model|Experiment
    {
        $data = $request->only(Experiment::getModel()->getFillable());

        return Experiment::updateOrCreate(['id' => $request->get('id')], $data);
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

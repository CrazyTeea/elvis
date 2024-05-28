<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMonkeyRequest;
use App\Http\Requests\UpdateMonkeyRequest;
use App\Models\Monkey;
use Illuminate\Support\Facades\DB;

class MonkeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Monkey::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        response()->abort('403', 'create route is turned off');
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreMonkeyRequest $request)
    {

        return Monkey::updateOrCreate(
            ['id' => $request->get('id')],
            [
                'name' => $request->get('name'),
                'elvis_id' => $request->get('elvis_id'),
                'age' => $request->get('age'),
                'weight' => $request->get('weight'),
                'comment' => $request->get('comment'),
            ]
        );


    }

    /**
     * Display the specified resource.
     */
    public function show(Monkey $monkey)
    {
        return $monkey;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monkey $monkey)
    {
        response()->abort('403', 'edit route is turned off');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMonkeyRequest $request, Monkey $monkey)
    {
        response()->abort('403', 'update route is turned off');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Monkey $monkey)
    {
        return Monkey::destroy($monkey->id);
    }
}

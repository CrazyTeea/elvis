<?php

use App\Http\Controllers\ExperimentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MonkeyController;
use Illuminate\Support\Facades\Route;

Route::resource('monkeys', MonkeyController::class);

Route::prefix('experiment')->group(function () {
    Route::post('store', [ExperimentController::class, 'store'])->name('experiment.store');
    Route::post('store-figures', [ExperimentController::class, 'storeFigures'])->name('experiment.store-figures');
    Route::get('{experiment_id}/generate-file', [ExperimentController::class, 'generateFile'])->name('experiment.generate-file');
    Route::get('kek', [ExperimentController::class, 'test'])->name('experiment.kek');
    Route::post('send-com', [ExperimentController::class, 'sendStimul'])->name('experiment.send-com');
    Route::post('store-exp2-results', [ExperimentController::class, 'storeExp2Res'])->name('experiment.store-exp2-results');
});

Route::prefix('files')->group(function () {
    Route::post('add/{number}/{monkey_id}', [FileController::class, 'generateFile'])->name('files.add');
    Route::get('download/{id}', [FileController::class, 'downloadFile'])->name('files.download');
    Route::post('delete/{id}', [FileController::class, 'deleteFile'])->name('files.delete');
});

Route::get('/{any}', function () {
    return view('main');
})->where('any', '.*');



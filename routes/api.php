<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\BankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/banks', [BankController::class, 'index'])->name('banks');

Route::post('/agent', [AgentController::class, 'show'])->name('agent.create');
Route::post('/agent/create', [AgentController::class, 'create'])->name('agent.create');
Route::post('/agent/add-card', [AgentController::class, 'addCard'])->name('agent.add-card');

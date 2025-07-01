<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\OutgoingPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/banks', [BankController::class, 'index'])->name('banks');

Route::post('/agent', [AgentController::class, 'show'])->name('agent.show');
Route::post('/agent/create', [AgentController::class, 'create'])->name('agent.create');
Route::post('/agent/add-card', [AgentController::class, 'addCard'])->name('agent.add-card');

// Route::post('/outgoing-payment', [OutgoingPaymentController::class, 'show'])->name('agent.create');

Route::get('/agent/{agent}/outgoing-payments', [OutgoingPaymentController::class, 'index'])->name('outgoingPayment.index');
Route::post('/agent/{agent}/outgoing-payments/{outgoingPayment}/update', [OutgoingPaymentController::class, 'update'])->name('outgoingPayment.update');

// Route::post('/outgoing-payment/add-card', [OutgoingPaymentController::class, 'addCard'])->name('agent.add-card');

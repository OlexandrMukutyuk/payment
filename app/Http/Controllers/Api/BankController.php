<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::active()->get();

        return response()->json([
            'data' => [
                'banks' => BankResource::collection($banks),
                'result' => true,
        ]]);
    }
}

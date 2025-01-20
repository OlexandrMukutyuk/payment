<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Agent\CreateRequest;
use App\Http\Requests\Api\Agent\ShowRequest;
use App\Http\Resources\Api\AgentResource;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function show(ShowRequest $request)
    {
        $agent = Agent::firstWhere($request->validated());
        // dd(1);

        return response()->json([
            'data' => [
                'banks' => AgentResource::make($agent),
                'result' => true,
        ]]);
    }

    public function create(CreateRequest $request)
    {
        $agent = Agent::create($request->validated());

        return response()->json([
            'data' => [
                'banks' => AgentResource::make($agent),
                'result' => true,
        ]]);
    }
}

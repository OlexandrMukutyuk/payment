<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Agent\AddCardRequest;
use App\Http\Requests\Api\Agent\CreateRequest;
use App\Http\Requests\Api\Agent\ShowRequest;
use App\Http\Resources\Api\AgentResource;
use App\Http\Resources\Api\CardResource;
use App\Models\Agent;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AgentController extends Controller
{
    public function show(ShowRequest $request)
    {
        $agent = Agent::firstWhere($request->validated());

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

    public function addCard(AddCardRequest $request)
    {
        $agent = Agent::firstWhere([
            'group_id' => $request->group_id,
            'chat_id' =>  $request->chat_id,
        ]);
        $card = Arr::except($request->validated(), ['group_id', 'chat_id', 'file']);
        $card['limit'] = Settings::firstWhere('key', 'limit')->value;
        $card = $agent->cards()->create($card);
        $card->addMedia($request->file)
            ->toMediaCollection('files', 'cards');
        $card->fresh();

        return response()->json([
            'data' => [
                'card' => CardResource::make($card),
                'result' => true,
        ]]);
    }

}

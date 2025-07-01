<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OutgoingPayment\CreateRequest;
use App\Http\Resources\Api\OutgoingPaymentResource;
use App\Models\Agent;
use App\Models\OutgoingPayment;
use Illuminate\Http\Request;

class OutgoingPaymentController extends Controller
{
    public function index(Agent $agent)
    {
        $active_card = $agent->active_card;
        if(!$active_card){
            abort(404, 'Active card not found!');
        }
        $outgoingPayments = OutgoingPayment::onlyNew()->where('sum', '<', $active_card->limit)->get();

        return response()->json([
                'OutgoingPayment' => OutgoingPaymentResource::collection($outgoingPayments),
                'result' => true,
        ]);
    }

    public function update(Agent $agent, OutgoingPayment $outgoingPayment, CreateRequest $request)
    {
        if($outgoingPayment->status != 'new')
        {
            abort(401, 'The request is already being processed by someone else.');
        }

        $active_card = $agent->active_card;

        if(!$active_card)
        {
            abort(404, 'Card not found.');
        }

        if($active_card->limit < $outgoingPayment->sum){
            abort(401, 'You have exceeded your limit.');
        }

        $outgoingPayment->update([
            'chat_id' => $agent->chat_id,
            'group_id' => $agent->group_id,
            'agent_id' => $agent->id,
            'bank' => $active_card->bank->name,
            'card' => $active_card->number,
            'fee' => $request->fee,
            'incoming_sum' => $request->incoming_sum,

        ]);

        $outgoingPayment->update([
            'status' => 'in_process'
        ]);

        $active_card->update([
            'limit' => $active_card->limit - $request->incoming_sum,
        ]);

        return response()->json([
            'data' => [
                'OutgoingPayment' => OutgoingPaymentResource::make($outgoingPayment->fresh()),
                'result' => true,
        ]]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IncomingPayment\CreateRequest;
use App\Http\Resources\Api\IncomingPaymentResource;
use App\Models\Agent;
use App\Models\IncomingPayment;
use Illuminate\Http\Request;

class IncomingPaymentController extends Controller
{
    public function index(Agent $agent)
    {
        $active_card = $agent->active_card;

        if(!$active_card){
            abort(404, 'Active card not found!');
        }
        $incomingPayment = IncomingPayment::onlyNew()
        // ->where('sum', '<', $active_card->limit)
        ->get();

        // dd($incomingPayment);
        return response()->json([
                'incomingPayments' => IncomingPaymentResource::collection($incomingPayment),
                'result' => true,
        ]);
    }

    public function take(Agent $agent, IncomingPayment $incomingPayment)
    {
        if($incomingPayment->status != 'new')
        {
            abort(401, 'The request is already being processed by someone else.');
        }

        $active_card = $agent->active_card;

        $incomingPayment->update([
            'status' => 'in_process',
            'chat_id' => $agent->chat_id,
            'group_id' => $agent->group_id,
            'agent_id' => $agent->id,
            'recipient_name' => $agent->name,
            'recipient_bank' => $active_card->bank->name,
            'recipient_card' => $active_card->number,
            'recipient_iban' => $active_card->iban,
        ]);

        return response()->json([
            'incomingPayment' => IncomingPaymentResource::make($incomingPayment->fresh()),
            'result' => true,
        ]);
    }

    public function cancel(Agent $agent, IncomingPayment $incomingPayment)
    {
        if($incomingPayment->status != 'in_process' ||
        $incomingPayment->agent_id != $agent->id ||
        $incomingPayment->group_id != $agent->group_id){
            abort(401, 'Its not your payment');
        }

        $incomingPayment->update([
            'status' => 'new',
            'chat_id' => null,
            'group_id' => null,
            'agent_id' => null,
            'recipient_name' => null,
            'recipient_bank' => null,
            'recipient_card' => null,
            'recipient_iban' => null,
        ]);

        return response()->json([
            'incomingPayment' => IncomingPaymentResource::make($incomingPayment->fresh()),
            'result' => true,
        ]);
    }

    public function update(Agent $agent, IncomingPayment $incomingPayment, CreateRequest $request)
    {
        if($incomingPayment->status != 'in_process' ||
        $incomingPayment->chat_id != $agent->chat_id ||
        $incomingPayment->group_id != $agent->group_id ||
        $incomingPayment->agent_id != $agent->id)
        {
            abort(401, 'The request is already being processed by someone else.');
        }

        $active_card = $agent->active_card;

        if(!$active_card)
        {
            abort(404, 'Card not found.');
        }

        $active_card = $agent->active_card;

        $incomingPayment->update([
            'incoming_sum' => $request->incoming_sum,
        ]);

        return response()->json([
            'incomingPayment' => IncomingPaymentResource::make($incomingPayment->fresh()),
            'result' => true,
        ]);
    }
}

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
                'outgoingPayment' => OutgoingPaymentResource::collection($outgoingPayments),
                'result' => true,
        ]);
    }

    public function take(Agent $agent, OutgoingPayment $outgoingPayment)
    {
        $active_card = $agent->active_card;

        if($outgoingPayment->status == 'in_process'){
            abort(401, 'The request is already taken.');
        }

        if(!$active_card)
        {
            abort(404, 'Card not found.');
        }

        if($active_card->limit < $outgoingPayment->sum){
            abort(401, 'You have exceeded your limit.');
        }

        $outgoingPayment->update([
            'status' => 'in_process',
            'chat_id' => $agent->chat_id,
            'group_id' => $agent->group_id,
            'agent_id' => $agent->id,
        ]);

        $active_card->update([
            'limit' => $active_card->limit - $outgoingPayment->sum,
        ]);

        return response()->json([
            'outgoingPayment' => OutgoingPaymentResource::make($outgoingPayment->fresh()),
            'result' => true,
        ]);
    }

    public function cancel(Agent $agent, OutgoingPayment $outgoingPayment)
    {
        if($outgoingPayment->status != 'in_process' ||
        $outgoingPayment->agent_id != $agent->id ||
        $outgoingPayment->group_id != $agent->group_id){
            abort(401, 'Its not your payment');
        }
        $active_card = $agent->active_card;

        $outgoingPayment->update([
            'status' => 'new',
            'chat_id' => null,
            'group_id' => null,
            'agent_id' => null,
        ]);

        $active_card->update([
            'limit' => $active_card->limit + $outgoingPayment->sum,
        ]);

        return response()->json([
            'outgoingPayment' => OutgoingPaymentResource::make($outgoingPayment->fresh()),
            'result' => true,
        ]);
    }

    public function update(Agent $agent, OutgoingPayment $outgoingPayment, CreateRequest $request)
    {
        if($outgoingPayment->status != 'in_process' ||
        $outgoingPayment->chat_id != $agent->chat_id ||
        $outgoingPayment->group_id != $agent->group_id ||
        $outgoingPayment->agent_id != $agent->id)
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
            'bank' => $active_card->bank->name,
            'card' => $active_card->number,
            'fee' => $request->fee,
            'incoming_sum' => $request->incoming_sum,
        ]);

        $outgoingPayment->addMedia($request->file('file'))->toMediaCollection('outgoing_payment_files');

        $active_card->update([
            'limit' => $active_card->limit - $request->incoming_sum,
        ]);

        return response()->json([
            'outgoingPayment' => OutgoingPaymentResource::make($outgoingPayment->fresh()),
            'result' => true,
        ]);
    }

}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'Id' => $this->id,
            'BalanceBefore' => $this->balance_before,
            'BalanceAfter' => $this->balance_after,
            'Amount' => $this->amount,
            'Participant' => $this->Participant,
            'RefNumber' => $this->ref_number,
            'Operator' => $this->operator,
            'Type' => $this->type,
        ];
    }
}
